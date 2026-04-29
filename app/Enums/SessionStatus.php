<?php
namespace App\Enums;
enum SessionStatus: string {
    case Scheduled   = 'scheduled';
    case Live        = 'live';
    case Completed   = 'completed';
    case Cancelled   = 'cancelled';
    case Rescheduled = 'rescheduled';
}
