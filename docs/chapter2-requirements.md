# Chapter 2: Analysis and Specification of Requirements

## 2.1 Introduction

This chapter presents the functional and technical analysis of the web application for student accounting management, dedicated to the tracking and billing of tuition fees, the management of payment records, and the administration of scholarships. The main objective is to clearly define the system requirements, the actors involved, as well as the expected functionalities, in order to guarantee a coherent design and a controlled implementation.

## 2.2 Problem Presentation

In many educational institutions, tuition fee management still relies on manual procedures or heterogeneous tools (spreadsheets, paper documents), which leads to:

- Errors in calculation and payment tracking
- A lack of visibility for students regarding their financial situation
- Difficulty in auditing operations carried out by the administration
- No data isolation between different universities or departments

This project aims to propose a centralized, secure, and automated solution to improve transparency, reliability, and efficiency in student accounting management across multiple universities and departments.

## 2.3 System Objectives

The main objectives of the system are:

- Support a multi-tenant architecture allowing management across multiple universities and departments
- Centralize the management of students, fees, and payments
- Automate the generation and tracking of invoices
- Allow students to consult their financial situation
- Manage scholarships and their allocation to students
- Ensure traceability and security of administrative actions
- Enforce strict data isolation between roles through scope-based access control

## 2.4 System Actors

The system is based on role-based access control (RBAC) with five distinct roles organized in a strict hierarchy. Each role has a rank that determines the scope of their permissions.

| Role | Rank | Scope |
|---|---|---|
| Super Administrator | 4 | Full system access |
| University Administrator | 3 | Own university only |
| Department Administrator | 2 | Own department only |
| Staff Administrator | 1 | Own department only |
| Student | 0 | Own data only (read-only) |

### 2.4.1 Super Administrator

The Super Administrator has full, unrestricted access to the entire system across all universities and departments.

Main responsibilities:

- Management of all user accounts regardless of university or department
- Creation and management of universities and departments
- Full access to all resources (fees, invoices, payments, scholarships)
- Consultation of audit logs tracing all critical actions

### 2.4.2 University Administrator

The University Administrator manages operations within their own university only.

Main responsibilities:

- Management of departments within their university
- Management of user accounts with a strictly lower rank within their university
- Management of fees, invoices, payments, and scholarship awards scoped to their university
- Consultation of audit logs scoped to their university

### 2.4.3 Department Administrator

The Department Administrator manages operations within their own department only.

Main responsibilities:

- Management of user accounts with a strictly lower rank within their department
- Management of fees, invoices, payments, and scholarship awards scoped to their department
- Can update their own department information

### 2.4.4 Staff Administrator

The Staff Administrator operates within their own department with slightly reduced privileges compared to the Department Administrator.

Main responsibilities:

- Management of student accounts within their department
- Recording of fees, invoices, payments, and scholarship awards scoped to their department
- Cannot update department information

### 2.4.5 Student

The Student is the end user of the system with read-only access to their own data exclusively.

Main responsibilities:

- Consultation of their university and department information
- Consultation of fees applicable to their department
- Consultation of their invoices and payment history
- Consultation of their scholarship awards

## 2.5 Functional Specification

### 2.5.1 Super Administrator Functionalities

- Full management of universities and departments
- Full management of all user and student accounts
- Full management of fees, invoices, payments, and scholarships
- Consultation of audit logs for all system actions

### 2.5.2 University Administrator Functionalities

- Management of departments within own university
- Management of user and student accounts within own university (lower rank only)
- Management of fees, invoices, payments, and scholarship awards within own university
- Consultation of audit logs scoped to own university

### 2.5.3 Department Administrator Functionalities

- Management of user and student accounts within own department (lower rank only)
- Management of fees, invoices, payments, and scholarship awards within own department
- Update of own department information

### 2.5.4 Staff Administrator Functionalities

- Management of student accounts within own department
- Recording of fees, invoices, payments, and scholarship awards within own department

### 2.5.5 Student Functionalities

- Secure authentication
- Consultation of own university and department details
- Consultation of fees applicable to their department
- Consultation of own invoices (status: paid, partially paid, unpaid, overdue)
- Consultation of own payment history
- Consultation of own scholarship awards

## 2.6 Non-Functional Specification

- **Security:** Secure authentication, role-based access control, protection of sensitive data, prevention of privilege escalation
- **Multi-tenancy:** Full data isolation between universities and departments — each role can only access data within their own scope
- **IDOR Protection:** Student-facing routes verify ownership explicitly — a student changing a URL parameter cannot access another student's data
- **Traceability:** All administrative actions are logged with user, event type, model, timestamp, IP address, and user agent
- **Reliability:** Financial records (invoices, payments, scholarship awards) are immutable — no deletion is permitted to preserve data integrity
- **Performance:** Eager loading is applied throughout to minimize database queries
- **Extensibility:** The architecture supports adding new roles, universities, or departments without structural changes

## 2.7 Conclusion

This chapter has allowed for a detailed definition of the functional and non-functional requirements of the system, as well as the roles and responsibilities of the five actors. These specifications form the basis for the technical design and implementation, which will be addressed in the following chapters.