<?php
namespace App\Enums;
enum CourseStatus: string {
    case Draft         = 'draft';
    case PendingReview = 'pending_review';
    case Live          = 'live';
    case Archived      = 'archived';
    case Suspended     = 'suspended';
}
