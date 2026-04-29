<?php
namespace App\Enums;
enum ClassStatus: string {
    case Draft     = 'draft';
    case Upcoming  = 'upcoming';
    case Active    = 'active';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
