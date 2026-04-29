<?php

namespace App\Repositories\Student\Contracts;

use App\Models\User;

interface StudentRegistrationRepositoryInterface
{
    /**
     * Register a new student user with profile and student profile.
     *
     * @param  array  $data  Validated registration data
     * @return User
     */
    public function register(array $data): User;
}
