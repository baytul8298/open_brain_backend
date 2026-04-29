<?php
namespace App\Enums;
enum AssignmentStatus: string {
    case Draft    = 'draft';
    case Active   = 'active';
    case Closed   = 'closed';
    case Archived = 'archived';
}
