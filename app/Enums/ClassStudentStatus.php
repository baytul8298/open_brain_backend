<?php
namespace App\Enums;
enum ClassStudentStatus: string {
    case Active    = 'active';
    case Suspended = 'suspended';
    case Completed = 'completed';
    case Withdrawn = 'withdrawn';
}
