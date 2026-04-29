<?php
namespace App\Enums;
enum SubmissionStatus: string {
    case NotSubmitted = 'not_submitted';
    case Submitted    = 'submitted';
    case Late         = 'late';
    case Graded       = 'graded';
    case Returned     = 'returned';
}
