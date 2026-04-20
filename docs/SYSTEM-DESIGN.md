# Database Design

## Main Tables

1. **universities** (id, name, city, created_at, updated_at)
2. **departments** (id, university_id, name, created_at, updated_at)
3. **users** (id, university_id, department_id, first_name, last_name, email, password, date_of_birth, role, is_active, created_at, updated_at)
4. **students** (id, user_id, academic_year, baccalaureate_year, study_system, level, created_at, updated_at)
5. **fees** (id, department_id, name, amount, academic_year, description, created_at, updated_at)
6. **invoices** (id, student_id, fee_id, status, issued_date, due_date, created_at, updated_at)
7. **payments** (id, invoice_id, payment_method, amount, reference, payment_date, created_at, updated_at)
8. **scholarships** (id, name, description, amount, created_at, updated_at)
9. **scholarship_awards** (id, student_id, scholarship_id, grant_date, end_date, status, paid_at, reference, created_at, updated_at)
10. **audit_logs** (id, user_id, event_type, model_type, model_id, ip_address, user_agent, created_at)

## Relationships

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
11. students → scholarship_awards → scholarships [many-to-many] — a student can receive multiple scholarships