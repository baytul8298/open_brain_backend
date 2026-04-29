<?php
namespace App\Enums;
enum PricingModel: string {
    case MonthlySubscription = 'monthly_subscription';
    case OneTime             = 'one_time';
    case Free                = 'free';
}
