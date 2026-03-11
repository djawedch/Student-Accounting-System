# Authorization Documentation

## Admin Roles

### University
- **viewAny:** super_admin, university_admin, department_admin, staff_admin
- **view:** super_admin, university_admin, department_admin, staff_admin
- **create:** super_admin
- **update:** super_admin, university_admin (own university)
- **delete:** super_admin

### Department
- **viewAny:** super_admin, university_admin, department_admin, staff_admin
- **view:** super_admin, university_admin, department_admin, staff_admin
- **create:** super_admin, university_admin (own university)
- **update:** super_admin, university_admin (own university), department_admin (own department)
- **delete:** super_admin, university_admin (own university)

### User / Student
- **viewAny:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **view:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **create:** super_admin, university_admin, department_admin, staff_admin (cannot create equal/higher role)
- **update:** same scope + must outrank target, cannot update self
- **delete:** same scope + must outrank target, cannot delete self
- **toggleStatus:** same scope + must outrank target, cannot toggle self

### Fee
- **viewAny:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **view:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **create:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **update:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **delete:** super_admin, university_admin (own university), department_admin/staff_admin (own department)

### Invoice
- **viewAny:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **view:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **create:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **update:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **delete:** no one

### Payment
- **viewAny:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **view:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **create:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **update:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **delete:** no one

### Scholarship
- **viewAny:** super_admin, university_admin, department_admin, staff_admin
- **view:** super_admin, university_admin, department_admin, staff_admin
- **create:** super_admin only
- **update:** super_admin only
- **delete:** super_admin only

### Scholarship Award
- **viewAny:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **view:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **create:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **update:** super_admin, university_admin (own university), department_admin/staff_admin (own department)
- **delete:** no one

### Audit Log
- **viewAny:** super_admin, university_admin (own university)
- **view:** super_admin, university_admin (own university)
- **create:** no one
- **update:** no one
- **delete:** no one

---

## Student Role

### University
- **show:** own university only (derived from `Auth::user()->university_id`)

### Department
- **show:** own department only (derived from `Auth::user()->department_id`)

### Fee
- **index:** own department's fees only
- **show:** own department's fees only — 403 if fee belongs to another department

### Invoice
- **index:** own invoices only
- **show:** own invoices only — 403 if invoice belongs to another student

### Payment
- **index:** own payments only
- **show:** own payments only — 403 if payment belongs to another student

### Scholarship Award
- **index:** own awards only
- **show:** own awards only — 403 if award belongs to another student

---

## Enforcement Layers

### 1. Middleware
`role:student` and `role:super_admin,university_admin,department_admin,staff_admin` block access at the route level before any controller logic runs.

### 2. Laravel Policies
Every model has a dedicated policy class in `app/Policies/`. All controller actions call `$this->authorize()` before processing.

### 3. Role Scope Layer
Every resource has a dedicated scope class in `app/Scopes/` that restricts query results based on the authenticated user's role and ownership.

### 4. Form Request Validation
`StoreInvoiceRequest`, `StorePaymentRequest`, and `StoreScholarshipAwardRequest` include custom validation rules that verify the submitted student/fee IDs belong to the authenticated user's university or department — preventing tampering with hidden form fields.

### 5. IDOR Protection (Student Routes)
All student-facing `show` routes verify ownership explicitly before returning data. A student changing a URL parameter to access another student's record receives a `403`.