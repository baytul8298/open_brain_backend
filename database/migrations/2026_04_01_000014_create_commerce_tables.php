<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Documents commerce schema tables created via SQL (registered as fake). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commerce.enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('student_id');
            $table->uuid('course_id');
            $table->string('status', 20)->default('active');
            $table->timestampTz('enrolled_at')->useCurrent();
            $table->timestampTz('expires_at')->nullable();
            $table->timestampTz('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->smallInteger('progress_pct')->default(0);
            $table->uuid('last_lesson_id')->nullable();
            $table->timestampTz('last_accessed_at')->nullable();
            $table->timestampTz('completed_at')->nullable();
            $table->uuid('certificate_id')->nullable();
            $table->decimal('current_plan_price', 10, 2)->default(0);
            $table->timestampsTz();
            $table->unique(['student_id', 'course_id']);
        });

        Schema::create('commerce.subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('enrollment_id');
            $table->uuid('student_id');
            $table->uuid('course_id');
            $table->string('status', 20)->default('active');
            $table->string('billing_cycle', 16)->default('monthly');
            $table->decimal('price_per_cycle', 10, 2);
            $table->char('currency', 3)->default('BDT');
            $table->timestampTz('current_period_start');
            $table->timestampTz('current_period_end');
            $table->timestampTz('next_billing_date')->nullable();
            $table->timestampTz('trial_end_at')->nullable();
            $table->timestampTz('cancelled_at')->nullable();
            $table->timestampTz('pause_until')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->timestampsTz();
        });

        Schema::create('commerce.coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('teacher_id')->nullable();
            $table->uuid('course_id')->nullable();
            $table->string('code', 32)->unique();
            $table->text('description')->nullable();
            $table->string('discount_type', 10);
            $table->decimal('discount_value', 10, 2);
            $table->integer('max_uses')->nullable();
            $table->integer('used_count')->default(0);
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->timestampTz('valid_from')->useCurrent();
            $table->timestampTz('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestampTz('created_at')->useCurrent();
        });

        Schema::create('commerce.payments', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('student_id');
            $table->uuid('course_id');
            $table->uuid('enrollment_id')->nullable();
            $table->uuid('subscription_id')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->decimal('gross_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->decimal('platform_fee', 10, 2);
            $table->decimal('teacher_amount', 10, 2);
            $table->char('currency', 3)->default('BDT');
            $table->string('status', 20)->default('pending');
            $table->integer('method_id')->nullable();
            $table->string('gateway', 32)->nullable();
            $table->string('gateway_txn_id', 128)->nullable()->unique();
            $table->jsonb('gateway_response')->nullable();
            $table->decimal('gateway_fee', 10, 2)->default(0);
            $table->string('invoice_number', 32)->nullable()->unique();
            $table->text('invoice_url')->nullable();
            $table->timestampTz('refunded_at')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->text('refund_reason')->nullable();
            $table->string('refund_txn_id', 128)->nullable();
            $table->timestampTz('paid_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('commerce.teacher_wallet', function (Blueprint $table) {
            $table->uuid('teacher_id')->primary();
            $table->decimal('available', 12, 2)->default(0);
            $table->decimal('pending', 12, 2)->default(0);
            $table->decimal('lifetime_earned', 14, 2)->default(0);
            $table->timestampTz('updated_at')->useCurrent();
        });

        Schema::create('commerce.payouts', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('teacher_id');
            $table->decimal('amount', 12, 2);
            $table->string('status', 20)->default('pending');
            $table->string('method', 32);
            $table->jsonb('account_info')->nullable();
            $table->timestampTz('processed_at')->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });

        Schema::create('commerce.wallet_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('teacher_id');
            $table->uuid('payment_id')->nullable();
            $table->uuid('payout_id')->nullable();
            $table->string('type', 16);
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->text('description')->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commerce.wallet_transactions');
        Schema::dropIfExists('commerce.payouts');
        Schema::dropIfExists('commerce.teacher_wallet');
        Schema::dropIfExists('commerce.payments');
        Schema::dropIfExists('commerce.coupons');
        Schema::dropIfExists('commerce.subscriptions');
        Schema::dropIfExists('commerce.enrollments');
    }
};
