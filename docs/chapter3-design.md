# Chapter 3: System Design

## 3.1 Introduction

This chapter describes the overall architecture and design of the student accounting management system **(payment recording system)**. The goal is to define a simple, maintainable, and secure structure that meets the core functional requirements outlined in Chapter 2, while remaining feasible for implementation within the PFE timeline.

## 3.2 System Architecture

The three-tier architecture (presentation / application / data)

1. **Presentation Layer (View)**: Laravel Blade templates with Tailwind CSS and JavaScript
2. **Application Layer (Controller/Model)**: PHP with Laravel framework handling business logic and data processing
3. **Data Layer**: MySQL database for persistent data storage

## 3.3 Technology Stack

To keep development simple and efficient, the following technologies are proposed:

- **Frontend**: Laravel Blade, Tailwind CSS, JavaScript
- **Backend**: PHP, Laravel framework
- **Database**: MySQL
- **Server**: Apache
- **Version Control**: Git, GitHub

## 3.4 Database Design

### Main tables:

1. **users** (id, email, password, role, is_active, remember_token, created_at, updated_at)
2. **students** (id, user_id, first_name, last_name, class, academic_year, created_at, updated_at)
3. **fees** (id, name, amount, description, academic_year, created_at, updated_at)
4. **invoices** (id, student_id, fee_id, status, issued_date, due_date, total_amount, amount_paid, balance_due, created_at, updated_at)
5. **payments** (id, invoice_id, payment_method, amount, reference, payment_date, created_at, updated_at)
6. **Scholarships** (id, student_id, type, amount, grant_date, end_date, description, status, paid_at, reference, created_at, updated_at)
7. **AuditLogs** (id, user_id, action, model_type, model_id, ip_address, user_agent, created_at)

### Column Clarifications:

1. **users**
- `role`: ENUM('super_admin','admin','student') - Defines the user's role for access control.
- `is_active`: BOOLEAN, default true - Indicates whether the user account is active. Inactive users cannot log in.
- `remember_token`: VARCHAR(100), nullable – Token for "remember me" functionality.

2. **students**
- `class`: VARCHAR(255) - The student's class or level (e.g., "L3 Computer Science").
- `academic_year`: VARCHAR(9) - Academic year (e.g., "2024-2025").

3. **fees**
- `name`: VARCHAR(255) - Fee name (e.g., "Tuition Fee", "Library Fee").
- `amount`: DECIMAL(10,2) - Fee amount in the local currency (Algerian Dinar).
- `description`: TEXT, nullable - Optional details about the fee.
- `academic_year`: VARCHAR(9) - Academic year the fee applies to (e.g., "2024-2025").

4. **invoices**
- `status`: ENUM('unpaid','partially_paid','paid','overdue') - Current payment status. Updated automatically when payments are recorded.
- `issued_date`: DATE - Date the invoice was generated.
- `due_date`: DATE - Deadline for full payment.
- `total_amount`: DECIMAL(10,2) - Original amount due (from the associated fee).
- `amount_paid`: DECIMAL(10,2), default 0 - Sum of all payments recorded against this invoice.
- `balance_due`: DECIMAL(10,2) - Remaining amount to be paid (total_amount – amount_paid).

5. **payments**
- `payment_method`: ENUM('cash','bank_transfer','ccp') - Method used for the payment: cash, bank transfer, or CCP (Algerian postal cheque).
- `amount`: DECIMAL(10,2) - Payment amount.
- `reference`: VARCHAR(255), nullable - Optional reference number (e.g., bank transaction ID, receipt number).
- `payment_date`: DATE - Date the payment was made (may differ from the date it was recorded).

6. **scholarships**
- `type`: VARCHAR(255) - Scholarship type (e.g., "Merit", "Study Abroad", "Social Aid").
- `amount`: DECIMAL(10,2) - Grant amount awarded.
- `grant_date`: DATE - Date the scholarship was awarded.
- `end_date`: DATE, nullable - Expiration or end date of the scholarship (if applicable).
- `description`: TEXT, nullable - Additional notes (e.g., destination, conditions).
- `status`: ENUM('awarded','paid','cancelled'), default 'awarded'- Current state of the grant.
- `paid_at`: DATE, nullable - Date the money was actually transferred/paid to the student.
- `reference`: VARCHAR(255), nullable - Cheque number, transaction ID, or other external reference.

7. **auditlogs**
- `action`: VARCHAR(255) - Description of the action performed (e.g., "created", "updated", "deleted").
- `model_type`: VARCHAR(255) - Full class name of the affected model (e.g., App\Models\Fee).
- `model_id`: BIGINT - ID of the affected record.
- `ip_address`: VARCHAR(45), nullable - Client IP address at the time of the action.
- `user_agent`: TEXT, nullable - Browser/user agent string.

### Object Examples:

1. **users**
- (1, superadmin@univ.edu, password1_hashed, super_admin, 1, NULL, 2026-02-01 10:00:00, 2026-02-01 10:00:00)
- (2, admin1@univ.edu, password2_hashed, admin, 1, NULL, 2026-02-01 10:05:00, 2026-02-01 10:05:00)
- (3, student.ahmed@univ.edu, password3_hashed, student, 1, NULL, 2026-02-01 10:10:00, 2026-02-01 10:10:00)

2. **students**
- (1, 3, Ahmed, Benali, L3 Computer Science, 2025-2026, 2026-02-01 10:10:00, 2026-02-01 10:10:00)

3. **fees**
- (1, Registration Fee, 500.00, Annual registration, 2025-2026, 2026-02-01 10:15:00, 2026-02-01 10:15:00)
- (2, Library Fee, 200.00, Library access and materials, 2025-2026, 2026-02-01 10:16:00, 2026-02-01 10:16:00)

4. **invoices**
- (1, 1, 1, unpaid, 2026-02-01, 2026-03-01, 500.00, 0.00, 500.00, 2026-02-01 10:20:00, 2026-02-01 10:20:00)
- (2, 1, 2, paid, 2026-02-01, 2026-03-01, 200.00, 200.00, 0.00, 2026-02-01 10:21:00, 2026-02-02 09:30:00)

5. **payments**
- (1, 2, cash, 200.00, NULL, 2026-02-02, 2026-02-02 09:30:00, 2026-02-02 09:30:00)

6. **scholarships**
- (1, 1, Study Abroad, 150000.00, 2026-02-05, 2026-12-31, "Erasmus+ mobility grant", paid, 2026-02-10, "CHQ-12345", 2026-02-05 11:00:00, 2026-02-10 16:00:00)
- (2, 1, Merit, 200000.00, 2026-02-05, NULL, "Academic excellence award", awarded, NULL, NULL, 2026-02-05 11:05:00, 2026-02-05 11:05:00)

7. **auditlogs**
- (1, 1, created, App\Models\Fee, 1, 192.168.1.100, "Mozilla/5.0 ...", 2026-02-01 10:15:00)
- (2, 1, created, App\Models\Invoice, 1, 192.168.1.100, "Mozilla/5.0 ...", 2026-02-01 10:20:00)
- (3, 2, updated, App\Models\Payment, 1, 192.168.1.101, "Mozilla/5.0 ...", 2026-02-02 09:30:00)
- (4, 2, created, App\Models\Scholarship, 1, 192.168.1.101, "Mozilla/5.0 ...", 2026-02-05 11:00:00)

### Relationships:

1. users → students [one‑to‑one] (Each user with role = student has exactly one student profile)
2. users → auditlogs [one‑to‑many] (A user can have many audit log entries.)
3. students → invoices [one‑to‑many] (A student can have many invoices (each for a different fee))
4. students → scholarships [one‑to‑many] (A student can receive many scholarship grants (independent))
5. fees → invoices [one‑to‑many] (A fee definition can be used in many invoices (one per student))
6. invoices → payments [one‑to‑many] (An invoice can receive many partial payments.)
7. auditlogs → users [many‑to‑one] (Each log entry belongs to a user)

## 3.5 System Modules

The application is divided into four main modules:

1. **Authentication Module**: Handles login, registration, and role-based access.
2. **Student Management Module**: Allows admins to add, edit, and view students.
3. **Fee & Invoice Module**: For creating fees, generating invoices, and tracking statuses.
4. **Payment Recording Module**: Allows admins to manually record offline payments (cash, bank transfer, CCP) and update invoice statuses.
5. **Reporting Module** (Basic): Generate simple reports on payments, outstanding invoices, and scholarship distributions.

## 3.6 Security Design

- **Authentication**: Laravel’s built-in session management with CSRF protection.
- **Authorization**: Role-based access control (RBAC) as defined in Chapter 2.
- **Data Protection**: Password hashing, SQL injection prevention via Eloquent ORM.
- **Logging**: All payment recordings and critical admin actions are logged in the AuditLogs table for traceability.

## 3.7 Interface Design (Wireframes)

Simple, clean interfaces are proposed:

- **Login Page**: Simple email/password form (system auto-detects role after authentication).
- **Super Admin Dashboard**: Additional section for system configuration and admin management.
- **Admin Dashboard**: Tabs for Students, Fees, Invoices, Payments, Scholarships.
- **Student Dashboard**: Summary of fees, invoices, payment history, and status.
- **Payment Recording Form**: Dropdown for payment method, input for reference/note, amount, and date.
- **Responsive Design**: Works on desktop and mobile via tailwind.

## 3.8 Conclusion

This chapter outlined a minimal, practical design for the student accounting system focused on **payment recording**. The architecture remains straightforward, the technology stack is common and well-documented, and the database is normalized for clarity. This simplified design ensures the project is achievable within the given timeline while still meeting core functional requirements, this simplified design provides a solid foundation that can be extended in the future with features like online payment integration, advanced reporting.