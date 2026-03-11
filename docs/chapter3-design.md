# Chapter 3: System Design

## 3.1 Introduction

This chapter describes the overall architecture and design of the student accounting management system. The goal is to define a simple, maintainable, and secure structure that meets the core functional requirements outlined in Chapter 2, while remaining feasible for implementation within the PFE timeline.

## 3.2 System Architecture

The application follows a three-tier architecture (presentation / application / data):

1. **Presentation Layer (View)**: Laravel Blade templates with Tailwind CSS and JavaScript
2. **Application Layer (Controller/Model)**: PHP with Laravel framework handling business logic, authorization, and data processing
3. **Data Layer**: MySQL database for persistent data storage

Within the application layer, three additional sub-layers are implemented:

- **Filter Layer** (`app/Filters/`): Each resource has a dedicated filter class that handles search and filtering logic extracted from controllers, using `->when()` chaining on query builders
- **Role Scope Layer** (`app/Scopes/`): Each resource has a dedicated scope class that restricts query results based on the authenticated user's role and ownership
- **Policy Layer** (`app/Policies/`): Each model has a dedicated Laravel Policy that enforces both role level and ownership scope on every action

## 3.3 Technology Stack

| Layer | Technology |
|---|---|
| Frontend | Laravel Blade, Tailwind CSS, JavaScript |
| Backend | PHP 8.x, Laravel 11 |
| Database | MySQL |
| Authentication | Laravel built-in Auth (registration disabled — admin-only user creation) |
| Version Control | Git, GitHub |

## 3.4 Database Design

### Main Tables

1. **universities** (id, name, city, created_at, updated_at)
2. **departments** (id, university_id, name, created_at, updated_at)
3. **users** (id, university_id, department_id, first_name, last_name, email, password, date_of_birth, role, is_active, created_at, updated_at)
4. **students** (id, user_id, academic_year, baccalaureate_year, study_system, level, created_at, updated_at)
5. **fees** (id, department_id, name, amount, academic_year, description, created_at, updated_at)
6. **invoices** (id, student_id, fee_id, status, issued_date, due_date, created_at, updated_at)
7. **payments** (id, invoice_id, payment_method, amount, reference, payment_date, created_at, updated_at)
8. **scholarships** (id, name, description, amount, created_at, updated_at)
9. **student_scholarship** (id, student_id, scholarship_id, grant_date, end_date, status, paid_at, reference, created_at, updated_at)
10. **audit_logs** (id, user_id, event_type, model_type, model_id, ip_address, user_agent, created_at)

### Relationships

1. users → students [one-to-one] — each user with role = student has exactly one student profile
2. users → audit_logs [one-to-many] — a user can have many audit log entries
3. users → universities [many-to-one] — a user belongs to a university
4. users → departments [many-to-one] — a user belongs to a department
5. universities → departments [one-to-many] — a university has many departments
6. departments → users [one-to-many] — a department has many users
7. departments → fees [one-to-many] — a department has many fee definitions
8. students → invoices [one-to-many] — a student can have multiple invoices
9. fees → invoices [one-to-many] — a fee definition can be used in many invoices
10. invoices → payments [one-to-many] — an invoice can receive many partial payments
11. students → student_scholarship → scholarships [many-to-many] — a student can receive multiple scholarships

## 3.5 System Modules

The application is divided into five main modules:

1. **Authentication Module**: Handles login and role-based redirection. Registration is disabled — user accounts are created exclusively by administrators.
2. **User & Student Management Module**: Allows administrators to create, edit, activate, and deactivate user and student accounts. Role hierarchy is enforced — a user cannot manage accounts of equal or higher rank.
3. **Fee & Invoice Module**: For defining fees per department, generating bulk invoices for multiple students at once, and tracking invoice statuses (paid, partially paid, unpaid, overdue).
4. **Payment Recording Module**: Allows administrators to manually record offline payments (cash, bank transfer, CCP), automatically updates invoice status based on total payments received.
5. **Scholarship Management Module**: Manages global scholarship definitions and bulk allocation of scholarships to students with grant dates, end dates, and payment references.
6. **Audit Log Module**: Tracks all administrative actions with user identity, event type, affected model, timestamp, IP address, and user agent. Accessible only to super_admin and university_admin.

## 3.6 Security Design

- **Authentication**: Laravel's built-in session management with CSRF protection on all forms
- **Authorization**: Five-level role-based access control (RBAC) enforced at three layers — middleware, policy, and form request validation
- **Role Hierarchy**: Users cannot create, update, delete, or toggle the status of accounts with equal or higher rank — preventing privilege escalation
- **Scope Isolation**: Every query is scoped to the authenticated user's university or department — a university_admin cannot access data from another university, and a department_admin cannot access data from another department
- **IDOR Protection**: Student-facing show routes explicitly verify that the requested record belongs to the authenticated student before returning data
- **Form Request Validation**: Store requests for invoices, payments, and scholarship awards include custom validation rules that verify submitted IDs belong to the authenticated user's scope — preventing tampering with form fields
- **Data Integrity**: Invoices, payments, and scholarship awards cannot be deleted by any role — preserving the integrity of financial records
- **Password Security**: All passwords are hashed using Laravel's built-in bcrypt hashing
- **SQL Injection Prevention**: All database queries use Laravel's Eloquent ORM with parameter binding

## 3.7 Interface Design

Simple, clean interfaces are implemented for each role:

- **Login Page**: Email and password form with automatic role-based redirection after authentication
- **Admin Dashboard**: Overview with navigation to students, fees, invoices, payments, scholarships, and audit logs — data scoped to the authenticated user's role
- **Student Dashboard**: Navigation to own university, department, fees, invoices, payments, and scholarship awards
- **Bulk Invoice Generation Form**: Cascading filters (university → department → level → study system) to select students and fees for bulk invoice creation
- **Bulk Scholarship Award Form**: Same cascading filter pattern for awarding scholarships to multiple students at once
- **Responsive Design**: All interfaces are responsive via Tailwind CSS utility classes

## 3.8 Conclusion

This chapter outlined the architecture and design of the student accounting management system. The three-tier MVC architecture is extended with dedicated filter, scope, and policy layers to enforce data isolation and security. The database is normalized across 10 tables covering all financial operations. The security design addresses authentication, authorization, scope isolation, IDOR protection, and data integrity — providing a solid and extensible foundation for the implementation described in the following chapters.