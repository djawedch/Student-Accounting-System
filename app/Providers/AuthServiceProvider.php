<?php

namespace App\Providers;

use App\Models\{University, Department, Fee, Invoice, Payment, Scholarship, ScholarshipAward, User, AuditLog};
use App\Policies\{UniversityPolicy, DepartmentPolicy, FeePolicy, InvoicePolicy, PaymentPolicy, ScholarshipPolicy, ScholarshipAwardPolicy, UserPolicy, AuditLogPolicy};
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        University::class => UniversityPolicy::class,
        Department::class => DepartmentPolicy::class,
        Fee::class => FeePolicy::class,
        Invoice::class => InvoicePolicy::class,
        Payment::class => PaymentPolicy::class,
        Scholarship::class => ScholarshipPolicy::class,
        ScholarshipAward::class => ScholarshipAwardPolicy::class,
        User::class => UserPolicy::class,
        AuditLog::class => AuditLogPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
