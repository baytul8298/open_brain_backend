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
use Illuminate\Support\Facades\Validator;
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
        $validator = Validator::make($request->all(), [
            'type'        => ['required', 'string', Rule::in(['degree', 'certificate'])],
            'title'       => ['required', 'string', 'max:200'],
            'institution' => ['nullable', 'string', 'max:200'],
            'start_year'  => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'end_year'    => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'cgpa'        => ['nullable', 'string', 'max:20'],
            'is_current'  => ['boolean']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $userId             = $request->user()->id;
        $data               = $request->all();
        $data['user_id']    = $userId;
        $data['is_current'] = $data['is_current'] ?? false;
        $data['sort_order'] = TeacherEducation::where('user_id', $userId)->max('sort_order') + 1;

        $education = TeacherEducation::create($data);

        $educations = TeacherEducation::where('user_id', $userId)->orderBy('id', 'desc')->get();

        return response()->json(['message' => 'Education entry added.', 'education' => $education, 'educations' => $educations], 201);
    }

    // ── PUT /teacher/profile/education/{id} ───────────────────────────────────

    public function updateEducation(Request $request, int $id): JsonResponse
    {
        $education = TeacherEducation::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'type'        => ['required', 'string', Rule::in(['degree', 'certificate'])],
            'title'       => ['required', 'string', 'max:200'],
            'institution' => ['nullable', 'string', 'max:200'],
            'start_year'  => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'end_year'    => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'cgpa'        => ['nullable', 'string', 'max:20'],
            'is_current'  => ['boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = $request->all();
        $education->update($data);

        $educations = TeacherEducation::where('user_id', $request->user()->id)->orderBy('id', 'desc')->get();

        return response()->json(['message' => 'Education entry updated.', 'education' => $education, 'educations' => $educations]);
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
            'start_month'      => ['nullable', 'string'],
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
            'subject_ids.*'     => ['integer', Rule::exists(Subject::class, 'id')],
            'grade_level_ids'   => ['nullable', 'array'],
            'grade_level_ids.*' => ['integer', Rule::exists(GradeLevel::class, 'id')],
            'session_types'     => ['nullable', 'array'],
            'session_types.*'   => ['string', Rule::in(['one-on-one', 'group'])],
        ]);

        $teacherProfile = TeacherProfile::firstOrCreate(['user_id' => $request->user()->id]);

        // Support partial updates — only sync/update fields that were actually sent
        if ($request->has('subject_ids'))     $teacherProfile->subjects()->sync($data['subject_ids'] ?? []);
        if ($request->has('grade_level_ids')) $teacherProfile->gradeLevels()->sync($data['grade_level_ids'] ?? []);
        if ($request->has('session_types'))   $teacherProfile->update(['session_types' => $data['session_types'] ?? []]);

        $teacherProfile->refresh()->load(['subjects', 'gradeLevels']);

        return response()->json([
            'success'      => true,
            'message'      => 'Subjects updated.',
            'subjects'     => $teacherProfile->subjects,
            'grade_levels' => $teacherProfile->gradeLevels,
            'session_types' => $teacherProfile->session_types ?? [],
        ]);
    }

    // ── POST /teacher/profile/teaching-styles ────────────────────────────────

    public function storeTeachingStyle(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'color'       => ['nullable', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $userId             = $request->user()->id;
        $data               = $request->all();
        $data['user_id']    = $userId;
        $data['color']      = $data['color'] ? $data['color'] : '#3498db';
        $data['sort_order'] = TeacherTeachingStyle::where('user_id', $userId)->max('sort_order') + 1;

        $style = TeacherTeachingStyle::create($data);

        return response()->json([
            'success' => true, 
            'message' => 'Teaching style added.', 
            'style' => $style
        ], 201);
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
        $validator = Validator::make($request->all(), [
            'is_flexible_time'        => ['nullable', 'boolean'],
            'availability'            => ['nullable', 'array'],
            'availability.*.day'      => ['required_with:availability', 'string'], 
            'availability.*.slots'    => ['required_with:availability', 'array'],
            //'availability.*.slots.*'  => ['date_format:h:i A'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $userId = $request->user()->id;
        $data = $request->all();

        $dayMap = [
            'Sat' => 0,
            'Sun' => 1,
            'Mon' => 2,
            'Tue' => 3,
            'Wed' => 4,
            'Thu' => 5,
            'Fri' => 6,
        ];

        DB::beginTransaction();

        try {
            TeacherAvailability::where('user_id', $userId)->delete();

            if (!empty($data['availability'])) {
                foreach ($data['availability'] as $item) {
                    $dayNumber = $dayMap[$item['day']] ?? null;
                    
                    if ($dayNumber === null) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid day: ' . $item['day']
                        ], 451);
                    }

                    foreach ($item['slots'] as $time) {
                        $time24 = \Carbon\Carbon::createFromFormat('h:i A', $time)->format('H:i');

                        TeacherAvailability::create([
                            'user_id'     => $userId,
                            'day_of_week' => $dayNumber,
                            'time_slot'   => $time24,
                        ]);
                    }
                }
            }

            if ($request->has('is_flexible_time')) {
                TeacherProfile::updateOrCreate(
                    ['user_id' => $userId],
                    ['is_flexible_time' => $data['is_flexible_time'] ?? false]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Availability updated.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $e->getMessage()
            ], 500);
        }
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
