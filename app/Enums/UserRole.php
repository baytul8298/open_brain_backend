<?php
namespace App\Enums;
enum UserRole: string {
    case Student    = 'student';
    case Teacher    = 'teacher';
    case Parent     = 'parent';
    case Admin      = 'admin';
    case SuperAdmin = 'super_admin';
}
