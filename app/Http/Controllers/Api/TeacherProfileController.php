<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Core\GradeLevel;
use App\Models\Core\Profile;
use App\Models\Core\Subject;
use App\Models\Core\TeacherAvailability;
use App\Models\Core\TeacherEducation;
use App\Models\Core\TeacherExperience;
use App\Models\Core\TeacherProfile;
use App\Models\Core\TeacherTeachingStyle;
use App\Models\Core\TeacherVerification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeacherProfileController extends Controller
{
    // ── GET /teacher/profile ──────────────────────────────────────────────────

    public function show(Request $request): JsonResponse
    {
        $userId  = $request->user()->id;
        $profile = Profile::find($userId);
        $teacher = TeacherProfile::where('user_id', $userId)
            ->with([
                'educations',
                'experience',
                'subjects',
                'gradeLevels',
                'teachingStyles',
                'availability',
                'verifications' => fn ($q) => $q->latest('submitted_at')->take(1),
            ])
            ->first();

        return response()->json([
            'profile' => [
                'bio'        => $profile?->bio ?? '',
                'first_name' => $profile?->first_name ?? '',
                'last_name'  => $profile?->last_name ?? '',
                'avatar_url' => $profile?->avatar_url ?? '',
                'cover_url'  => $profile?->cover_url ?? '',
            ],
            'teacher' => [
                'headline'         => $teacher?->headline ?? '',
                'verified'         => $teacher?->verified ?? false,
                'id_verified'      => $teacher?->id_verified ?? false,
                'is_flexible_time' => $teacher?->is_flexible_time ?? false,
                'session_types'    => $teacher?->session_types ?? [],
                'rating_avg'       => $teacher?->rating_avg ?? 0,
                'rating_count'     => $teacher?->rating_count ?? 0,
                'total_students'   => $teacher?->total_students ?? 0,
                'total_courses'    => $teacher?->total_courses ?? 0,
            ],
            'educations'          => $teacher?->educations ?? [],
            'experience'          => $teacher?->experience ?? [],
            'subjects'            => $teacher?->subjects ?? [],
            'grade_levels'        => $teacher?->gradeLevels ?? [],
            'teaching_styles'     => $teacher?->teachingStyles ?? [],
            'availability'        => $teacher?->availability ?? [],
            'latest_verification' => $teacher?->verifications->first(),
            'all_subjects'        => Subject::where('is_active', true)->orderBy('name')->get(['id', 'name', 'color', 'icon']),
            'all_grade_levels'    => GradeLevel::orderBy('sort_order')->get(['id', 'name']),
        ]);
    }

    // ── PUT /teacher/profile/about ────────────────────────────────────────────

    public function updateAbout(Request $request): JsonResponse
    {
        $data = $request->validate([
            'bio'      => ['nullable', 'string', 'max:2000'],
            'headline' => ['nullable', 'string', 'max:200'],
        ]);

        $userId = $request->user()->id;

        Profile::updateOrCreate(
            ['id' => $userId],
            ['bio' => $data['bio'] ?? '']
        );

        TeacherProfile::updateOrCreate(
            ['user_id' => $userId],
            ['headline' => $data['headline'] ?? null]
        );

        return response()->json(['message' => 'About section updated.']);
    }

    // ── POST /teacher/profile/education ──────────────────────────────────────

    public function storeEducation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'        => ['required', 'string', Rule::in(['degree', 'certificate'])],
            'title'       => ['required', 'string', 'max:200'],
            'institution' => ['nullable', 'string', 'max:200'],
            'start_year'  => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'end_year'    => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'is_current'  => ['boolean'],
        ]);

        $userId             = $request->user()->id;
        $data['user_id']    = $userId;
        $data['is_current'] = $data['is_current'] ?? false;
        $data['sort_order'] = TeacherEducation::where('user_id', $userId)->max('sort_order') + 1;

        $education = TeacherEducation::create($data);

        return response()->json(['message' => 'Education entry added.', 'education' => $education], 201);
    }

    // ── PUT /teacher/profile/education/{id} ───────────────────────────────────

    public function updateEducation(Request $request, int $id): JsonResponse
    {
        $education = TeacherEducation::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $data = $request->validate([
            'type'        => ['required', 'string', Rule::in(['degree', 'certificate'])],
            'title'       => ['required', 'string', 'max:200'],
            'institution' => ['nullable', 'string', 'max:200'],
            'start_year'  => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'end_year'    => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'is_current'  => ['boolean'],
        ]);

        $education->update($data);

        return response()->json(['message' => 'Education entry updated.', 'education' => $education]);
    }

    // ── DELETE /teacher/profile/education/{id} ────────────────────────────────

    public function destroyEducation(int $id, Request $request): JsonResponse
    {
        TeacherEducation::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail()
            ->delete();

        return response()->json(['message' => 'Education entry deleted.']);
    }

    // ── PUT /teacher/profile/experience ──────────────────────────────────────

    public function updateExperience(Request $request): JsonResponse
    {
        $data = $request->validate([
            'role_type'        => ['required', 'string', Rule::in(['teacher', 'tutor', 'expert', 'freelance', 'student', 'other'])],
            'start_month'      => ['nullable', 'integer', 'min:1', 'max:12'],
            'start_year'       => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'description'      => ['required', 'string', 'max:5000'],
            'expertise_tags'   => ['nullable', 'array'],
            'expertise_tags.*' => ['string', 'max:80'],
        ]);

        $userId             = $request->user()->id;
        $data['user_id']    = $userId;
        $data['is_current'] = true;

        $experience = TeacherExperience::updateOrCreate(
            ['user_id' => $userId, 'is_current' => true],
            $data
        );

        return response()->json(['message' => 'Experience updated.', 'experience' => $experience]);
    }

    // ── PUT /teacher/profile/subjects ─────────────────────────────────────────

    public function updateSubjects(Request $request): JsonResponse
    {
        $data = $request->validate([
            'subject_ids'       => ['nullable', 'array'],
            'subject_ids.*'     => ['integer', 'exists:core.subjects,id'],
            'grade_level_ids'   => ['nullable', 'array'],
            'grade_level_ids.*' => ['integer', 'exists:core.grade_level,id'],
            'session_types'     => ['nullable', 'array'],
            'session_types.*'   => ['string', Rule::in(['one-on-one', 'group'])],
        ]);

        $teacherProfile = TeacherProfile::firstOrCreate(['user_id' => $request->user()->id]);

        $teacherProfile->subjects()->sync($data['subject_ids'] ?? []);
        $teacherProfile->gradeLevels()->sync($data['grade_level_ids'] ?? []);
        $teacherProfile->update(['session_types' => $data['session_types'] ?? []]);

        return response()->json(['message' => 'Subjects updated.']);
    }

    // ── POST /teacher/profile/teaching-styles ────────────────────────────────

    public function storeTeachingStyle(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'color'       => ['nullable', 'string', 'max:20'],
        ]);

        $userId             = $request->user()->id;
        $data['user_id']    = $userId;
        $data['sort_order'] = TeacherTeachingStyle::where('user_id', $userId)->max('sort_order') + 1;

        $style = TeacherTeachingStyle::create($data);

        return response()->json(['message' => 'Teaching style added.', 'style' => $style], 201);
    }

    // ── PUT /teacher/profile/teaching-styles/{id} ────────────────────────────

    public function updateTeachingStyle(Request $request, int $id): JsonResponse
    {
        $style = TeacherTeachingStyle::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'color'       => ['nullable', 'string', 'max:20'],
        ]);

        $style->update($data);

        return response()->json(['message' => 'Teaching style updated.', 'style' => $style]);
    }

    // ── DELETE /teacher/profile/teaching-styles/{id} ─────────────────────────

    public function destroyTeachingStyle(int $id, Request $request): JsonResponse
    {
        TeacherTeachingStyle::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail()
            ->delete();

        return response()->json(['message' => 'Teaching style removed.']);
    }

    // ── PUT /teacher/profile/availability ────────────────────────────────────

    public function updateAvailability(Request $request): JsonResponse
    {
        $data = $request->validate([
            'is_flexible_time' => ['required', 'boolean'],
            'slots'            => ['nullable', 'array'],
            'slots.*.day'      => ['required', 'integer', 'min:0', 'max:6'],
            'slots.*.time'     => ['required', 'date_format:H:i'],
        ]);

        $userId = $request->user()->id;

        DB::transaction(function () use ($userId, $data) {
            TeacherAvailability::where('user_id', $userId)->delete();

            if (!empty($data['slots'])) {
                $rows = array_map(fn ($slot) => [
                    'user_id'     => $userId,
                    'day_of_week' => $slot['day'],
                    'time_slot'   => $slot['time'],
                ], $data['slots']);

                $unique = collect($rows)
                    ->unique(fn ($r) => $r['day_of_week'] . '_' . $r['time_slot'])
                    ->values()
                    ->all();

                TeacherAvailability::insert($unique);
            }

            TeacherProfile::updateOrCreate(
                ['user_id' => $userId],
                ['is_flexible_time' => $data['is_flexible_time']]
            );
        });

        return response()->json(['message' => 'Availability updated.']);
    }

    // ── POST /teacher/profile/verification ───────────────────────────────────

    public function storeVerification(Request $request): JsonResponse
    {
        $data = $request->validate([
            'doc_type' => ['required', 'string', Rule::in(['ssc', 'hsc', 'honors', 'masters'])],
            'document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $userId = $request->user()->id;
        $path   = $request->file('document')->store("teacher-verifications/{$userId}", 'private');

        $verification = TeacherVerification::create([
            'user_id'  => $userId,
            'doc_type' => $data['doc_type'],
            'doc_url'  => $path,
            'status'   => 'pending',
        ]);

        TeacherProfile::updateOrCreate(
            ['user_id' => $userId],
            ['id_document_url' => $path]
        );

        return response()->json([
            'message'      => 'Verification document submitted. We will review it within 24–48 hours.',
            'verification' => $verification,
        ], 201);
    }
}
