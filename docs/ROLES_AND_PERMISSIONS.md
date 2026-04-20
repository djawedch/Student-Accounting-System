# ROLES & PERMISSIONS:

## UniversityController:

- **super_admin** → full CRUD (create, read, update, delete) on any university.
- **university_admin** → read only on all universities, plus update access only on the university they belong to (no create/delete).
- **department_admin** → read only on all universities, no write actions.
- **staff_admin** → read only on all universities, no write actions.
- **student** → can view the details of the university they belong to (via Auth::user()->university_id). The show method loads the university with its departments. Read only access; no create, update, or delete actions.

## DepartmentController:

- **super_admin** → full CRUD (create, read, update, delete) on any department, can assign to any university. Delete only allowed if no associated users or fees (controller level check).
- **university_admin** → read all departments, plus create departments (only for their own university), update departments that belong to their university, delete departments that belong to their university (if no users/fees). Cannot create/update/delete departments of other universities.
- **department_admin** → read all departments, plus update access only on the department they belong to (name only, cannot change university). No create, no delete.
- **staff_admin** → read only on all departments, no write actions.
- **student** → can view the details of the department they belong to (via Auth::user()->department_id). The show method loads the department with its associated university and fees. Read only access; no create, update, or delete actions.

## UserController:

- **super_admin** → full CRUD (create, read, update, delete) on any admin user, can toggle status. Can create/update users with any role lower than super_admin (i.e., university_admin, department_admin, staff_admin, student — but student creation might be elsewhere). Can view all admin users. Cannot manage themselves (update/delete/toggle self blocked by policy). Can assign any university/department.
- **university_admin** → can list, view, create, update, delete, and toggle status only for users who belong to the same university and have a strictly lower role rank (i.e., department_admin, staff_admin, student). Cannot manage themselves, cannot manage super_admin or other university_admins. When creating a user, role choices are limited to ranks lower than university_admin (department_admin, staff_admin, student). University selection is fixed to their own university; department choices are limited to departments within their university.
- **department_admin** → can list, view, create, update, delete, and toggle status only for users who belong to the same department and have a strictly lower role rank (i.e., staff_admin, student). Cannot manage themselves, cannot manage users of equal or higher rank (including other department_admins). When creating a user, role choices are limited to ranks lower than department_admin (staff_admin, student). Department selection is fixed to their own department; university is auto assigned from that department.
- **staff_admin** → can list, view, create, update, delete, and toggle status only for users who belong to the same department and have a strictly lower role rank (i.e., student only, since staff_admin has rank 1 and student has rank 0). Cannot manage themselves, cannot manage any other staff_admin or higher roles. When creating a user, role choices are limited to ranks lower than staff_admin (student only). Department selection is fixed to their own department; university is auto assigned from that department.
- **student** → no access to this controller at all (policy viewAny excludes student, and controller is inside admin group). Students are managed via a separate StudentController.

## StudentController:

- **super_admin** → full CRUD (create, read, update, delete via toggleStatus) on any student. Can assign student to any university and any department. Can toggle student active/inactive status. All student operations allowed without hierarchical restrictions.
- **university_admin** → can list, view, create, update, and toggle status only for students who belong to the same university (matching user.university_id). When creating a student, university selection is fixed to their own university; department choices are limited to departments within that university. Cannot manage students of other universities.
- **department_admin** → can list, view, create, update, and toggle status only for students who belong to the same department (matching user.department_id). When creating a student, department selection is fixed to their own department; university is auto assigned from that department. Cannot manage students of other departments.
- **staff_admin** → can list, view, create, update, and toggle status only for students who belong to the same department (matching user.department_id). When creating a student, department selection is fixed to their own department; university is auto assigned from that department. Cannot manage students of other departments. (Same scope as department_admin for students, because both have department_id and policy canManageUser uses same rule for both.)
- **student** → no access to this controller at all (policy viewAny excludes student role, and controller is inside admin group).

## FeeController:

- **super_admin** → full CRUD (create, read, update, delete) on any fee. Can assign fee to any department. Delete blocked only if fee has associated invoices (controller level check).
- **university_admin** → can list, view, create, update, and delete fees only for departments belonging to their own university. When creating a fee, department choices are limited to departments within their university. Cannot manage fees of other universities.
- **department_admin** → can list, view, create, update, and delete fees only for their own department (matching user.department_id). When creating a fee, department selection is fixed to their own department. Cannot manage fees of other departments.
- **staff_admin** → same as department_admin: can list, view, create, update, and delete fees only for their own department. When creating a fee, department selection is fixed to their own department. Cannot manage fees of other departments.
- **student** → can list all fees belonging to their own department (via $user->department->fees). Can view details of a specific fee only if that fee belongs to their department (otherwise 403). Read only access; no create, update, or delete actions.

## InvoiceController:

- **super_admin** → full read (list, view) and create invoices for any student and any fee. Can update invoice status, issued date, due date. Cannot delete invoices (policy delete always returns false). Can generate invoices in bulk (multiple students × multiple fees), with duplicate detection. Can view all invoices regardless of university/department.
- **university_admin** → can list, view, create, and update invoices only for students and fees belonging to their own university. When creating invoices, the request authorisation validates that all selected students have university_id matching the admin’s university, and all selected fees belong to departments within that university. Cannot delete invoices. Cannot access invoices of other universities.
- **department_admin** → can list, view, create, and update invoices only for students and fees belonging to their own department. When creating invoices, the request authorisation validates that all selected students have department_id matching the admin’s department, and all selected fees belong to that same department. Cannot delete invoices. Cannot access invoices of other departments.
- **staff_admin** → same as department_admin: can list, view, create, and update invoices only for students and fees belonging to their own department. When creating invoices, the request authorisation validates that all selected students have department_id matching the admin’s department, and all selected fees belong to that same department. Cannot delete invoices. Cannot access invoices of other departments.
- **student** → can list all invoices associated with their own student profile (via $user->student->invoices). Can view details of a specific invoice only if the invoice belongs to them ($invoice->student_id === $user->student->id). Read only access; no create, update, or delete actions.

## PaymentController:

- **super_admin** → full read (list, view) and create payments for any invoice. Can update payment amount, method, reference, and date. Cannot delete payments (policy delete always returns false). Can view all payments regardless of university/department. When creating a payment, can select any invoice with remaining balance.
- **university_admin** → can list, view, create, and update payments only for invoices belonging to students within their own university (matching student.user.university_id). When creating a payment, the request authorisation validates that the selected invoice’s student belongs to their university. Cannot delete payments. Cannot access payments for other universities.
- **department_admin** → can list, view, create, and update payments only for invoices belonging to students within their own department (matching student.user.department_id). When creating a payment, the request authorisation validates that the selected invoice’s student belongs to their department. Cannot delete payments. Cannot access payments for other departments.
- **staff_admin** → same as department_admin: can list, view, create, and update payments only for invoices belonging to students within their own department. Cannot delete payments. Cannot access payments for other departments.
- **student** → can list all payments associated with their own student profile (via collecting payments from their invoices). Can view details of a specific payment only if the payment belongs to one of their own invoices (checks that payment->invoice_id is in their list of invoice IDs). Read only access; no create, update, or delete actions.

## ScholarshipController:

- **super_admin** → full CRUD (create, read, update, delete) on any scholarship. Delete allowed only if the scholarship has not been awarded to any student (controller level check).
- **university_admin** → read only (list and view scholarships). Cannot create, update, or delete.
- **department_admin** → read only (list and view scholarships). Cannot create, update, or delete.
- **staff_admin** → read only (list and view scholarships). Cannot create, update, or delete.
- **student** → no access to this controller at all (policy viewAny excludes student role, and controller is inside admin group).

## ScholarshipAwardController:

- **super_admin** → full read (list, view) and create scholarship awards for any student and any scholarship. Can update existing awards (status, dates, reference, etc.). Cannot delete awards (policy delete always returns false). Can create awards in bulk (multiple students × multiple scholarships).
- **university_admin** → can list, view, create, and update scholarship awards only for students belonging to their own university. When creating awards, the request authorisation validates that all selected students have university_id matching the admin’s university. Cannot delete awards. Cannot access awards for other universities.
- **department_admin** → can list, view, create, and update scholarship awards only for students belonging to their own department. When creating awards, the request authorisation validates that all selected students have department_id matching the admin’s department. Cannot delete awards. Cannot access awards for other departments.
- **staff_admin** → can list, view, and create scholarship awards only for students belonging to their own department (same as department_admin for create and view). However, cannot update any award (the UpdateScholarshipAwardRequest authorisation explicitly returns false for staff_admin). Cannot delete awards. Cannot access awards for other departments.
- **student** → can list all scholarship awards associated with their own student profile (via $user->student->scholarshipAwards). Can view details of a specific award only if the award belongs to them ($award->student_id === $user->student->id). Read only access; no create, update, or delete actions.

## AuditLogController:

- **super_admin** → can list and view any audit log entry, regardless of which user or entity it relates to. Full read access to all audit logs.
- **university_admin** → can list and view audit log entries only where the associated user (auditLog.user) belongs to their own university (matching university_id). Cannot view logs of other universities or logs where the user is null/unrelated.
- **department_admin** → no access to this controller at all (policy viewAny excludes department_admin). Cannot list or view audit logs.
- **staff_admin** → no access to this controller at all (policy viewAny excludes staff_admin). Cannot list or view audit logs.
- **student** → no access to this controller at all (policy excludes student, controller inside admin group).

## DashboardController:

- **super_admin** → full access to dashboard with unrestricted data. Can see total students, invoices, collections, unpaid/overdue/paid/partially paid invoices, scholarship awards, and additionally total universities and total departments (visible only to super_admin). Payment chart shows all payments across the entire system.
- **university_admin** → access to dashboard but data is scoped only to their own university. Can see total students, invoices, collections, unpaid/overdue/paid/partially paid invoices, and scholarship awards filtered to their university. Payment chart shows payments only from invoices belonging to their university. Cannot see total universities or total departments (these are nulled).
- **department_admin** → access to dashboard but data is scoped only to their own department. Can see total students, invoices, collections, unpaid/overdue/paid/partially paid invoices, and scholarship awards filtered to their department. Payment chart shows payments only from invoices belonging to their department. Cannot see total universities or total departments.
- **staff_admin** → same as department_admin: access to dashboard with data scoped only to their own department. Can see all the same metrics filtered to their department. Payment chart scoped accordingly.
- **student** → can access their own dashboard, which displays their user information (name, email, etc.). Read only view; no actions for modifying data.

## ExportController:

- **super_admin** → can download any single invoice PDF (invoicePdf), any single payment PDF (paymentPdf), export full list of payments as PDF (paymentsListPdf), and export full list of invoices as PDF (invoicesListPdf). No data restrictions.
- **university_admin** → can download invoice PDF only for invoices belonging to students within their own university (via InvoicePolicy::view). Can download payment PDF only for payments linked to invoices within their own university (via PaymentPolicy::view). Can export payments list PDF scoped to their university (via PaymentRoleScope). Can export invoices list PDF scoped to their university (via InvoiceRoleScope).
- **department_admin** → can download invoice PDF only for invoices belonging to students within their own department. Can download payment PDF only for payments linked to invoices within their own department. Can export payments list PDF scoped to their department. Can export invoices list PDF scoped to their department.
- **staff_admin** → same as department_admin: can download invoice PDF and payment PDF only for records within their own department. Can export both payments and invoices lists scoped to their department.
- **student** → no access to any export method (controller is inside admin group, and policies exclude student role).