# Chapter 2: Analysis and Specification of Requirements

## 2.1 Introduction

This chapter presents the functional and technical analysis of the web application for student accounting management, dedicated to the tracking and billing of tuition fees, the management of payment records, and the administration of scholarships. The main objective is to clearly define the system requirements, the actors involved, as well as the expected functionalities, in order to guarantee a coherent design and a controlled implementation.

## 2.2 Problem Presentation

In many educational institutions, tuition fee management still relies on manual procedures or heterogeneous tools (spreadsheets, paper documents), which leads to:

- Errors in calculation and payment tracking
- A lack of visibility for students regarding their financial situation
- Difficulty in auditing operations carried out by the administration

This project aims to propose a centralized, secure, and automated solution to improve transparency, reliability, and efficiency in student accounting management.

## 2.3 System Objectives

The main objectives of the system are:

- Centralize the management of students, fees, and payments
- Automate the generation and tracking of invoices
- Allow students to consult their financial situation
- Manage scholarships and their impact on the amounts to be paid
- Ensure traceability and security of administrative actions

## 2.4 System Actors

The system is based on role-based management (RBAC – Role-Based Access Control).

Three main actors are identified:

### 2.4.1 Super Administrator

The Super Administrator is responsible for the overall management of the system.

Main responsibilities:

- Management of Administrator accounts (creation, modification, deletion)
- Configuration of global application settings (institution name, logo, currency, academic year)
- Full access to all Administrator functionalities
- Supervision and auditing of critical actions via a logging system

### 2.4.2 Administrator

The Administrator manages daily operations related to student accounting.

Main responsibilities:

- Management of student accounts
- Definition and assignment of tuition fees
- Generation and tracking of invoices
- Recording of payments (cash, transfer)
- Management of scholarships awarded to students

### 2.4.3 Student

The Student is the end user of the system.

Main responsibilities:

- Consultation of their fees and invoices
- Consultation of payment history
- Downloading of invoices and receipts

## 2.5 Functional Specification

### 2.5.1 Super Administrator Functionalities

- Full management of Administrator accounts
- System configuration (global settings)
- Access to all Administrator functionalities
- Consultation of audit logs tracing critical actions

### 2.5.2 Administrator Functionalities

**User Management:**

- Create, modify, and delete student accounts
- Associate students with a class or academic year

**Fee Management:**

- Define fee structures by department or year
- Assign fees to individual students or groups

**Billing:**

- Automatic generation of invoices
- Consultation of invoice status (Paid, Partially paid, Unpaid, Overdue)

**Payments:**

- Manual recording of payments performed outside the system
- Specification of payment method
- Automatic calculation of remaining balance
- Generation of payment receipt

**Scholarships:**

- Creation of different types of scholarships
- Allocation of scholarships to students with automatic adjustment of amounts

### 2.5.3 Student Functionalities

- Secure authentication
- Financial dashboard (total amount, paid, remaining)
- Detailed consultation of fees and invoices
- Downloading of invoices in PDF format
- Consultation of payment history

## 2.6 Non-Functional Specification

- **Security:** Secure authentication, management of roles and permissions, protection of sensitive data
- **Traceability:** Logging of critical actions by Administrators
- **Reliability:** Integrity of financial data
- **Performance:** Acceptable response time for common operations
- **Extensibility:** Possibility of adding new functionalities or roles in the future

## 2.7 Conclusion

This chapter has allowed for a detailed definition of the functional and non-functional requirements of the system, as well as the roles and responsibilities of the various actors. These specifications form the basis for the technical design and implementation, which will be addressed in the following chapters.