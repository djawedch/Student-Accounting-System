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

1. **users** (id, email, password, role)
2. **students** (id, user_id, first_name, last_name, class, academic_year)
3. **fees** (id, name, amount, description, academic_year)
4. **invoices** (id, student_id, fee_id, status, due_date, issued_date, total_amount, amount_paid, balance_due)
5. **payments** (id, invoice_id, payment_method, amount, reference, payment_date, recorded_by)

→ payment_method: cash, bank_transfer, ccp

1. **Scholarships** (id, student_id, type, amount, start_date, end_date, recorded_by)
2. **AuditLogs** (id, user_id, action)

### Relationships:

- **One User** has **one Role** (super_admin, admin, or student) – (stored as a column in users table)
- **One Student** (via user) can have **many Invoices**
- **One Fee** can generate many **Invoices** for **different Students**
- **One Invoice** can have **many Payments** (partial payments)
- **One Scholarship** reduces **Invoice amounts** for **one Student**
- **All Admin actions** are logged in **AuditLogs**

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