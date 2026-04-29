<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\GradeLevelRepositoryInterface;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Repositories\Student\Contracts\StudentRegistrationRepositoryInterface;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class StudentRegisterController extends Controller
{
    public function __construct(
        private readonly StudentRegistrationRepositoryInterface $repository,
        private readonly GradeLevelRepositoryInterface $gradeLevelRepository,
        private readonly SubjectRepositoryInterface $subjectRepository,
    ) {}

    public function create()
    {
        return Inertia::render('Auth/StudentRegister', [
            'classNames' => $this->gradeLevelRepository->all(),
            'subjects'   => $this->subjectRepository->all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'  => ['required', 'string', 'max:80'],
            'last_name'   => ['required', 'string', 'max:80'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'password'    => ['required', 'confirmed', new StrongPassword],
            'class_level' => ['nullable', Rule::exists('grade_level', 'id')],
            'subjects'    => ['nullable', 'array'],
            'subjects.*'  => [Rule::exists('subjects', 'id')],
            'agreed'      => ['accepted'],
        ]);

        $this->repository->register($validated);

        return redirect()->route('login')
            ->with('success', 'Registration successful. Your account is pending approval. You will be notified once an administrator approves it.');
    }
}
