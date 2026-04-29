<?php
namespace App\Enums;
enum EnrollmentStatus: string {
    case Active    = 'active';
    case Paused    = 'paused';
    case Cancelled = 'cancelled';
    case Expired   = 'expired';
    case Pending   = 'pending';
}
