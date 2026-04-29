<?php

namespace App\Providers;

use App\Repositories\Contracts\ModuleRepositoryInterface;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\SubModuleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Admin\ModuleRepository;
use App\Repositories\Admin\PermissionRepository;
use App\Repositories\Admin\RoleRepository;
use App\Repositories\Admin\SubModuleRepository;
use App\Repositories\Admin\UserRepository;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Repositories\Admin\SubjectRepository;
use App\Repositories\Contracts\ClassNameRepositoryInterface;
use App\Repositories\Admin\ClassNameRepository;
use App\Repositories\Contracts\GradeLevelRepositoryInterface;
use App\Repositories\Admin\GradeLevelRepository;
use App\Repositories\Student\Contracts\StudentRegistrationRepositoryInterface;
use App\Repositories\Student\StudentRegistrationRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(ModuleRepositoryInterface::class, ModuleRepository::class);
        $this->app->bind(SubModuleRepositoryInterface::class, SubModuleRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);
        $this->app->bind(ClassNameRepositoryInterface::class, ClassNameRepository::class);
        $this->app->bind(GradeLevelRepositoryInterface::class, GradeLevelRepository::class);
        $this->app->bind(StudentRegistrationRepositoryInterface::class, StudentRegistrationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
