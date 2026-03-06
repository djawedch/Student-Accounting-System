<?php

namespace App\Providers;

use App\Models\{University, Department, Fee, Invoice, Payment, Scholarship, StudentScholarship, User, AuditLog};
use App\Policies\{UniversityPolicy, DepartmentPolicy, FeePolicy, InvoicePolicy, PaymentPolicy, ScholarshipPolicy, StudentScholarshipPolicy, UserPolicy, AuditLogPolicy};
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        University::class => UniversityPolicy::class,
        Department::class => DepartmentPolicy::class,
        Fee::class => FeePolicy::class,
        Invoice::class => InvoicePolicy::class,
        Payment::class => PaymentPolicy::class,
        Scholarship::class => ScholarshipPolicy::class,
        StudentScholarship::class => StudentScholarshipPolicy::class,
        User::class => UserPolicy::class,
        AuditLog::class => AuditLogPolicy::class,
    ];
}
