<?php
namespace App\Enums;
enum CertificateStatus: string {
    case NotEarned = 'not_earned';
    case Earned    = 'earned';
    case Issued    = 'issued';
    case Revoked   = 'revoked';
}
