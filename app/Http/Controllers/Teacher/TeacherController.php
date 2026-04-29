<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function dashboard(): Response
    {
        $user     = Auth::user();
        $roles    = $user->roles;
        $roleName = $roles->first()->name;

        return Inertia::render('Teacher/Dashboard/Index');
    }

    public function profile(): Response
    {
        return Inertia::render('Teacher/Profile/Index');
    }

    public function students(): Response
    {
        return Inertia::render('Teacher/Students/Index');
    }

    public function assignments(): Response
    {
        return Inertia::render('Teacher/Assignments/Index');
    }

    public function courses(): Response
    {
        return Inertia::render('Teacher/Courses/Index');
    }

    public function createCourse(): Response
    {
        return Inertia::render('Teacher/Courses/Create');
    }

    public function analytics(): Response
    {
        return Inertia::render('Teacher/Analytics/Index');
    }
}
