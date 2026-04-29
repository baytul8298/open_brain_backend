<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Core\Profile;
use App\Models\Core\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class StudentProfileController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();
        $user->load(['profile', 'studentProfile.gradeLevel']);

        $profile        = $user->profile;
        $studentProfile = $user->studentProfile;

        // Fetch board_type enum values from PostgreSQL
        $boardTypes = DB::select("SELECT unnest(enum_range(NULL::board_type)) AS value");
        $boardOptions = array_map(fn ($row) => $row->value, $boardTypes);

        return Inertia::render('Student/Profile/Index', [
            'profileData' => [
                'bio'           => $profile?->bio ?? '',
                'first_name'    => $profile?->first_name ?? '',
                'last_name'     => $profile?->last_name ?? '',
                'display_name'  => $profile?->display_name ?? '',
                'date_of_birth' => $profile?->date_of_birth?->format('d F Y') ?? '',
                'contact_no'    => $profile?->contact_no ?? '',
                'email'         => $user->email ?? '',
                'city'          => $profile?->city ?? '',
                'country'       => $profile?->country ?? '',
                'avatar_url'    => $profile?->avatar_url ?? '',
                'cover_url'     => $profile?->cover_url ?? '',
                'gender'        => $profile?->gender ?? '',
            ],
            'studentData' => [
                'school_name'    => $studentProfile?->school_name ?? '',
                'board'          => $studentProfile?->board ?? '',
                'roll_number'    => $studentProfile?->roll_number ?? '',
                'grade_level'    => $studentProfile?->gradeLevel?->name ?? '',
                'grade_level_bn' => $studentProfile?->gradeLevel?->name_bn ?? '',
            ],
            'boardOptions' => $boardOptions,
        ]);
    }

    public function updateAbout(Request $request): RedirectResponse
    {
        $request->validate([
            'bio' => ['nullable', 'string', 'max:2000'],
        ]);

        Profile::updateOrCreate(
            ['id' => Auth::id()],
            ['bio' => $request->input('bio', '')]
        );

        return back();
    }

    public function updatePersonalInfo(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name'    => ['required', 'string', 'max:100'],
            'last_name'     => ['required', 'string', 'max:100'],
            'date_of_birth' => ['nullable', 'date'],
            'contact_no'    => ['nullable', 'string', 'max:20'],
            'city'          => ['nullable', 'string', 'max:100'],
            'country'       => ['nullable', 'string', 'max:100'],
            'school_name'   => ['nullable', 'string', 'max:200'],
            'board'         => ['nullable', 'string', Rule::in($this->getBoardTypes())],
        ]);

        // Update core.profiles
        Profile::updateOrCreate(
            ['id' => Auth::id()],
            $request->only(['first_name', 'last_name', 'date_of_birth', 'contact_no', 'city', 'country'])
        );

        // Update core.student_profiles
        StudentProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only(['school_name', 'board'])
        );

        return back();
    }

    public function dashboard(): Response
    {
        return Inertia::render('Student/Dashboard/Index');
    }

    private function getBoardTypes(): array
    {
        $rows = DB::select("SELECT unnest(enum_range(NULL::board_type)) AS value");

        return array_map(fn ($row) => $row->value, $rows);
    }
}
