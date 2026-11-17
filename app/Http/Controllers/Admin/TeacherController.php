<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Core\Profile;
use App\Models\Core\Subject;
use App\Models\Core\TeacherProfile;
use App\Models\Core\TeacherType;
use App\Models\Core\TeacherVerification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TeacherController extends Controller
{
    public function index(Request $request): Response
    {
        $queueSearch        = $request->get('queue_search');
        $queueSubjectId     = $request->get('queue_subject_id');
        $queueTeacherTypeId = $request->get('queue_teacher_type_id');

        $search        = $request->get('search');
        $subjectId     = $request->get('subject_id');
        $teacherTypeId = $request->get('teacher_type_id');
        $status        = $request->get('status');

        // Verification Queue (pending_verification teachers, 10 per page)
        $queueQuery = $this->baseTeacherQuery()
            ->where('status', UserStatus::PendingVerification->value);

        $this->applyTeacherFilters($queueQuery, $queueSearch, $queueSubjectId, $queueTeacherTypeId);

        $verificationQueue = $queueQuery
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'queue_page')
            ->withQueryString()
            ->through(fn (User $user) => $this->transformTeacher($user));

        // All Teachers (non-pending teachers, 15 per page)
        $teachersQuery = $this->baseTeacherQuery()
            ->where('status', '!=', UserStatus::PendingVerification->value);

        $this->applyTeacherFilters($teachersQuery, $search, $subjectId, $teacherTypeId);

        if ($status) {
            $teachersQuery->where('status', $status);
        }

        $teachers = $teachersQuery
            ->orderByDesc('created_at')
            ->paginate(15, ['*'], 'page')
            ->withQueryString()
            ->through(fn (User $user) => $this->transformTeacher($user));

        $totalTeachers   = $this->baseTeacherQuery()->count();
        $activeTeachers  = $this->baseTeacherQuery()->where('status', UserStatus::Active->value)->count();
        $pendingTeachers = $this->baseTeacherQuery()->where('status', UserStatus::PendingVerification->value)->count();
        $avgRating       = (float) (TeacherProfile::query()
            ->where('rating_count', '>', 0)
            ->avg('rating_avg') ?? 0);

        return Inertia::render('Admin/Teachers/Index', [
            'verificationQueue' => $verificationQueue,
            'teachers'          => $teachers,
            'subjects'          => Subject::where('is_active', true)->orderBy('name')->get(['id', 'name', 'color']),
            'teacherTypes'      => TeacherType::orderBy('id')->get(['id', 'name']),
            'stats'             => [
                'total'      => $totalTeachers,
                'active'     => $activeTeachers,
                'pending'    => $pendingTeachers,
                'avg_rating' => number_format($avgRating, 1),
            ],
            'filters'           => [
                'queue_search'          => $queueSearch,
                'queue_subject_id'      => $queueSubjectId,
                'queue_teacher_type_id' => $queueTeacherTypeId,
                'search'                => $search,
                'subject_id'            => $subjectId,
                'teacher_type_id'       => $teacherTypeId,
                'status'                => $status,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'username'         => ['required', 'string', 'max:100', 'unique:users,username'],
            'email'            => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'            => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'password'         => ['required', 'string', 'min:6'],
            'status'           => ['required', Rule::in([UserStatus::PendingVerification->value, UserStatus::Active->value])],
            'teacher_type_id'  => ['nullable', 'integer', Rule::exists(TeacherType::class, 'id')],
            'subject_ids'      => ['nullable', 'array'],
            'subject_ids.*'    => ['integer', Rule::exists(Subject::class, 'id')],
            'headline'         => ['nullable', 'string', 'max:200'],
            'institution'      => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:80'],
            'bio'              => ['nullable', 'string', 'max:2000'],
            'address'          => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $isActive = $validated['status'] === UserStatus::Active->value;

                $user = new User([
                    'email'             => $validated['email'],
                    'phone'             => $validated['phone'] ?? null,
                    'username'          => $validated['username'],
                    'password_hash'     => $validated['password'],
                    'salt'              => base64_encode($validated['password']),
                    'role'              => UserRole::Teacher,
                    'user_type'         => 'teacher',
                    'status'            => $validated['status'],
                    'language'          => 'en',
                    'email_verified'    => $isActive,
                    'phone_verified'    => $isActive,
                    'email_verified_at' => $isActive ? now() : null,
                ]);
                $user->id = (string) Str::uuid();
                $user->save();

                $teacherRole = Role::whereIn('name', ['teacher', 'wb-teacher'])->first();
                if ($teacherRole) {
                    $user->syncRoles([$teacherRole->id]);
                }

                $nameParts = $this->splitName($validated['name']);
                Profile::create([
                    'id'           => $user->id,
                    'first_name'   => $nameParts['first'],
                    'last_name'    => $nameParts['last'],
                    'display_name' => $validated['name'],
                    'contact_no'   => $validated['phone'] ?? null,
                    'address'      => $validated['address'] ?? null,
                    'bio'          => $validated['bio'] ?? null,
                    'language'     => 'en',
                ]);

                $defaultTypeId = $validated['teacher_type_id']
                    ?? TeacherType::orderBy('id')->value('id');

                $teacherProfile = TeacherProfile::create([
                    'user_id'          => $user->id,
                    'teacher_type_id'  => $defaultTypeId,
                    'headline'         => $validated['headline'] ?? null,
                    'institution'      => $validated['institution'] ?? null,
                    'experience_years' => $validated['experience_years'] ?? null,
                    'verified'         => $isActive,
                    'verified_at'      => $isActive ? now() : null,
                    'verified_by'      => $isActive ? auth()->id() : null,
                ]);

                if (!empty($validated['subject_ids'])) {
                    $teacherProfile->subjects()->sync($validated['subject_ids']);
                }
            });

            return redirect()->back()->with('success', 'Teacher created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create teacher: ' . $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'username'         => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($id)],
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'phone'            => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($id)],
            'password'         => ['nullable', 'string', 'min:6'],
            'status'           => ['required', Rule::in(array_column(UserStatus::cases(), 'value'))],
            'teacher_type_id'  => ['nullable', 'integer', Rule::exists(TeacherType::class, 'id')],
            'subject_ids'      => ['nullable', 'array'],
            'subject_ids.*'    => ['integer', Rule::exists(Subject::class, 'id')],
            'headline'         => ['nullable', 'string', 'max:200'],
            'institution'      => ['nullable', 'string', 'max:255'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:80'],
            'bio'              => ['nullable', 'string', 'max:2000'],
            'address'          => ['nullable', 'string', 'max:500'],
        ]);

        $user = $this->baseTeacherQuery()->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Teacher not found.');
        }

        try {
            DB::transaction(function () use ($user, $validated) {
                $isActive = $validated['status'] === UserStatus::Active->value;

                $userUpdate = [
                    'username' => $validated['username'],
                    'email'    => $validated['email'],
                    'phone'    => $validated['phone'] ?? null,
                    'status'   => $validated['status'],
                ];

                if (!empty($validated['password'])) {
                    $userUpdate['password_hash'] = $validated['password'];
                    $userUpdate['salt']          = base64_encode($validated['password']);
                }

                if ($isActive && !$user->email_verified) {
                    $userUpdate['email_verified']    = true;
                    $userUpdate['phone_verified']    = true;
                    $userUpdate['email_verified_at'] = $user->email_verified_at ?? now();
                }

                $user->update($userUpdate);

                $nameParts = $this->splitName($validated['name']);
                $profileData = [
                    'first_name'   => $nameParts['first'],
                    'last_name'    => $nameParts['last'],
                    'display_name' => $validated['name'],
                    'contact_no'   => $validated['phone'] ?? null,
                    'address'      => $validated['address'] ?? null,
                    'bio'          => $validated['bio'] ?? null,
                ];

                if ($user->profile) {
                    $user->profile->update($profileData);
                } else {
                    Profile::create(array_merge(['id' => $user->id, 'language' => 'en'], $profileData));
                }

                $profileAttrs = [
                    'teacher_type_id'  => $validated['teacher_type_id'] ?? $user->teacherProfile?->teacher_type_id,
                    'headline'         => $validated['headline'] ?? null,
                    'institution'      => $validated['institution'] ?? null,
                    'experience_years' => $validated['experience_years'] ?? null,
                ];

                if ($isActive && !($user->teacherProfile?->verified)) {
                    $profileAttrs['verified']    = true;
                    $profileAttrs['verified_at'] = now();
                    $profileAttrs['verified_by'] = auth()->id();
                }

                $teacherProfile = TeacherProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    $profileAttrs
                );

                if (array_key_exists('subject_ids', $validated)) {
                    $teacherProfile->subjects()->sync($validated['subject_ids'] ?? []);
                }
            });

            return redirect()->back()->with('success', 'Teacher updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update teacher: ' . $e->getMessage());
        }
    }

    public function approve(string $id)
    {
        $user = $this->baseTeacherQuery()->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Teacher not found.');
        }

        try {
            DB::transaction(function () use ($user) {
                $user->update([
                    'status'            => UserStatus::Active->value,
                    'phone_verified'    => true,
                    'email_verified'    => true,
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);

                TeacherProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'verified'    => true,
                        'verified_at' => now(),
                        'verified_by' => auth()->id(),
                    ]
                );

                TeacherVerification::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->update([
                        'status'      => 'approved',
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                    ]);
            });

            return redirect()->back()->with('success', 'Teacher approved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to approve teacher: ' . $e->getMessage());
        }
    }

    public function reject(string $id)
    {
        $user = $this->baseTeacherQuery()->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Teacher not found.');
        }

        try {
            DB::transaction(function () use ($user) {
                $user->update([
                    'status' => UserStatus::Suspended->value,
                ]);

                TeacherProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    ['verified' => false]
                );

                TeacherVerification::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->update([
                        'status'      => 'rejected',
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                    ]);
            });

            return redirect()->back()->with('success', 'Teacher rejected.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reject teacher: ' . $e->getMessage());
        }
    }

    public function toggleStatus(string $id)
    {
        $user = $this->baseTeacherQuery()->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Teacher not found.');
        }

        $currentStatus = $user->getRawOriginal('status');
        $newStatus     = $currentStatus === UserStatus::Active->value
            ? UserStatus::Suspended->value
            : UserStatus::Active->value;

        $user->update(['status' => $newStatus]);

        return redirect()->back()->with(
            'success',
            $newStatus === UserStatus::Active->value ? 'Teacher activated.' : 'Teacher suspended.'
        );
    }

    public function destroy(string $id)
    {
        $user = $this->baseTeacherQuery()->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Teacher not found.');
        }

        try {
            $user->delete();

            return redirect()->back()->with('success', 'Teacher deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete teacher: ' . $e->getMessage());
        }
    }

    private function baseTeacherQuery(): Builder
    {
        return User::query()
            ->with([
                'profile',
                'teacherProfile.teacherType',
                'teacherProfile.subjects',
                'teacherProfile.verifications',
            ])
            ->where(function ($q) {
                $q->where('role', UserRole::Teacher->value)
                    ->orWhere('user_type', 'teacher')
                    ->orWhereHas('roles', fn ($r) => $r->whereIn('name', ['teacher', 'wb-teacher']));
            });
    }

    private function applyTeacherFilters(
        Builder $query,
        ?string $search,
        mixed $subjectId,
        mixed $teacherTypeId
    ): void {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('profile', function ($p) use ($search) {
                    $p->where('display_name', 'ilike', "%{$search}%")
                        ->orWhere('first_name', 'ilike', "%{$search}%")
                        ->orWhere('last_name', 'ilike', "%{$search}%");
                })
                    ->orWhere('username', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%")
                    ->orWhere('phone', 'ilike', "%{$search}%");
            });
        }

        if (!empty($subjectId)) {
            $query->whereHas('teacherProfile.subjects', fn ($s) => $s->where('core.subjects.id', $subjectId));
        }

        if (!empty($teacherTypeId)) {
            $query->whereHas('teacherProfile', fn ($tp) => $tp->where('teacher_type_id', $teacherTypeId));
        }
    }

    private function transformTeacher(User $user): array
    {
        $profile        = $user->profile;
        $teacherProfile = $user->teacherProfile;
        $fullName       = trim(($profile?->first_name ?? '') . ' ' . ($profile?->last_name ?? ''));
        $displayName    = $profile?->display_name ?: ($fullName ?: $user->username);
        $rawStatus      = $user->getRawOriginal('status') ?: ($user->status?->value ?? (string) $user->status);

        $verifications = ($teacherProfile?->verifications ?? collect())->map(fn ($v) => [
            'id'           => $v->id,
            'doc_type'     => $v->doc_type,
            'doc_url'      => $v->doc_url ? Storage::url($v->doc_url) : null,
            'status'       => $v->status,
            'submitted_at' => $v->submitted_at?->toIso8601String(),
        ])->values()->all();

        return [
            'id'               => $user->id,
            'name'             => $displayName,
            'username'         => $user->username,
            'email'            => $user->email,
            'phone'            => $user->phone,
            'address'          => $profile?->address,
            'bio'              => $profile?->bio,
            'status'           => $rawStatus,
            'teacher_type_id'  => $teacherProfile?->teacher_type_id,
            'teacher_type'     => $teacherProfile?->teacherType?->name ?? 'General',
            'headline'         => $teacherProfile?->headline,
            'institution'      => $teacherProfile?->institution,
            'experience_years' => $teacherProfile?->experience_years,
            'verified'         => (bool) ($teacherProfile?->verified ?? false),
            'subjects'         => ($teacherProfile?->subjects ?? collect())->map(fn ($s) => [
                'id'    => $s->id,
                'name'  => $s->name,
                'color' => $s->color,
            ])->values()->all(),
            'subject_ids'      => ($teacherProfile?->subjects ?? collect())->pluck('id')->values()->all(),
            'courses_count'    => (int) ($teacherProfile?->total_courses ?? 0),
            'students_count'   => (int) ($teacherProfile?->total_students ?? 0),
            'total_revenue'    => (float) ($teacherProfile?->total_revenue ?? 0),
            'rating_avg'       => number_format((float) ($teacherProfile?->rating_avg ?? 0), 1),
            'verifications'    => $verifications,
            'created_at'       => $user->created_at?->toIso8601String(),
        ];
    }

    private function splitName(string $name): array
    {
        $parts = explode(' ', trim($name), 2);

        return [
            'first' => $parts[0] ?? '',
            'last'  => $parts[1] ?? '',
        ];
    }
}
