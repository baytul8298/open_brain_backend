<?php
namespace App\Enums;
enum ClassType: string {
    case Live     = 'live';
    case Recorded = 'recorded';
    case Hybrid   = 'hybrid';
}
