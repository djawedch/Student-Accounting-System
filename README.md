# 🎓 Student Accounting System

A multi-tenant university fee and scholarship management system built with Laravel.  
Designed to handle the full financial lifecycle of students across multiple universities and departments.

---

## ✨ Features

### 🏛️ Multi-Tenancy
- Supports multiple universities, each with their own departments
- Full data isolation — admins only access data within their own scope
- Scalable architecture ready for SaaS deployment

### 🔐 Role-Based Access Control (5 Roles)
| Role | Scope |
|---|---|
| `super_admin` | Full system access |
| `university_admin` | Own university only |
| `department_admin` | Own department only |
| `staff_admin` | Own department only |
| `student` | Own data only (read-only) |

- Hierarchical role enforcement — users cannot create or modify accounts of equal or higher rank
- Scope-based permissions on every resource

### 💰 Financial Management
- **Fee definitions** per department and academic year
- **Invoice generation** linking students to fees
- **Multiple payments per invoice** — tracks partial and full payments
- **Immutable records** — payments and invoices can never be deleted (audit integrity)

### 🎓 Scholarship Management
- Global scholarship definitions managed by super admin
- Award scholarships to individual students with grant/end dates
- Payment reference tracking per award

### 📋 Audit Logging
- Every action is logged with user, event type, model, timestamp, IP address, and user agent
- Accessible only to super_admin and university_admin
- Provides full accountability and traceability

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11, PHP 8.x |
| Frontend | Blade, Tailwind CSS, Vite |
| Database | MySQL |
| Auth | Laravel built-in Auth (customized — registration disabled, admin-only user creation) |

---

## 🏗️ Architecture

### Filter Layer (`app/Filters/`)
Every resource has a dedicated filter class that handles search and filtering logic extracted from controllers, using `->when()` chaining on query builders.

### Role Scope Layer (`app/Scopes/`)
Every resource has a dedicated role scope class that restricts query results based on the authenticated user's role and ownership — university_admin sees only their university's data, department_admin and staff_admin see only their department's data.

### Policy Layer (`app/Policies/`)
Every model is protected by a dedicated Laravel Policy that enforces both role level and ownership scope on every action.

---

## 🗃️ Database Design

**10 core tables:**
`universities` · `departments` · `users` · `students` · `fees` · `invoices` · `payments` · `scholarships` · `student_scholarship` · `audit_logs`

---

## ⚙️ Installation

```bash
# Clone the repository
git clone https://github.com/djawedch/Student-Accounting-System.git
cd Student-Accounting-System

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure your database in .env, then run:
php artisan migrate --seed

# Build assets
npm run dev
```

---

## 🔑 Default Roles & Seeded Accounts

After running migrations with seeders, the following roles are available:

| Role | Description |
|---|---|
| `super_admin` | Full system control |
| `university_admin` | Manages a single university |
| `department_admin` | Manages a single department |
| `staff_admin` | Handles student operations in a department |
| `student` | Views own fees, invoices, payments, and scholarships |

---

## 🔒 Authorization Highlights

- Every model is protected by a dedicated **Laravel Policy**
- Permission checks enforce both **role level** and **ownership scope**
- Students have read-only access to their own data exclusively
- Invoices, payments, and scholarship awards are **non-deletable** by design to preserve financial integrity
- Students access their own university and department in read-only mode, with IDOR protection on all show routes
- Role hierarchy prevents privilege escalation — no user can create or modify an account of equal or higher rank

---

## 👨‍💻 Author

**Djawed Chaibdra**  
Laravel Backend Developer  
[GitHub](https://github.com/djawedch)
