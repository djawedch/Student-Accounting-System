# Role Capabilities by Model

## super_admin
Can do everything on every model — no restrictions.

| Model | viewAny | view | create | update | delete |
|---|---|---|---|---|---|
| University | ✅ | ✅ | ✅ | ✅ | ✅ |
| Department | ✅ | ✅ | ✅ | ✅ | ✅ |
| User | ✅ | ✅ | ✅ | ✅ | ✅ |
| Student | ✅ | ✅ | ✅ | ✅ | ✅ |
| Fee | ✅ | ✅ | ✅ | ✅ | ✅ |
| Invoice | ✅ | ✅ | ✅ | ✅ | ❌ |
| Payment | ✅ | ✅ | ✅ | ✅ | ❌ |
| Scholarship | ✅ | ✅ | ✅ | ✅ | ✅ |
| ScholarshipAward | ✅ | ✅ | ✅ | ✅ | ❌ |
| AuditLog | ✅ | ✅ | ❌ | ❌ | ❌ |

---

## university_admin
Scoped to own university only.

| Model | viewAny | view | create | update | delete |
|---|---|---|---|---|---|
| University | ✅ | ✅ own | ❌ | ✅ own | ❌ |
| Department | ✅ | ✅ | ✅ own uni | ✅ own uni | ✅ own uni |
| User | ✅ own uni | ✅ own uni | ✅ lower rank only | ✅ own uni + outrank | ✅ own uni + outrank |
| Student | ✅ own uni | ✅ own uni | ✅ lower rank only | ✅ own uni + outrank | ✅ own uni + outrank |
| Fee | ✅ own uni | ✅ own uni | ✅ own uni | ✅ own uni | ✅ own uni |
| Invoice | ✅ own uni | ✅ own uni | ✅ own uni | ✅ own uni | ❌ |
| Payment | ✅ own uni | ✅ own uni | ✅ own uni | ✅ own uni | ❌ |
| Scholarship | ✅ | ✅ | ❌ | ❌ | ❌ |
| ScholarshipAward | ✅ own uni | ✅ own uni | ✅ own uni | ✅ own uni | ❌ |
| AuditLog | ✅ own uni | ✅ own uni | ❌ | ❌ | ❌ |

---

## department_admin
Scoped to own department only.

| Model | viewAny | view | create | update | delete |
|---|---|---|---|---|---|
| University | ✅ | ✅ | ❌ | ❌ | ❌ |
| Department | ✅ | ✅ | ❌ | ✅ own dept | ❌ |
| User | ✅ own dept | ✅ own dept | ✅ lower rank only | ✅ own dept + outrank | ✅ own dept + outrank |
| Student | ✅ own dept | ✅ own dept | ✅ lower rank only | ✅ own dept + outrank | ✅ own dept + outrank |
| Fee | ✅ own dept | ✅ own dept | ✅ own dept | ✅ own dept | ✅ own dept |
| Invoice | ✅ own dept | ✅ own dept | ✅ own dept | ✅ own dept | ❌ |
| Payment | ✅ own dept | ✅ own dept | ✅ own dept | ✅ own dept | ❌ |
| Scholarship | ✅ | ✅ | ❌ | ❌ | ❌ |
| ScholarshipAward | ✅ own dept | ✅ own dept | ✅ own dept | ✅ own dept | ❌ |
| AuditLog | ❌ | ❌ | ❌ | ❌ | ❌ |

---

## staff_admin
Same scope as department_admin — own department only. Cannot update departments.

| Model | viewAny | view | create | update | delete |
|---|---|---|---|---|---|
| University | ✅ | ✅ | ❌ | ❌ | ❌ |
| Department | ✅ | ✅ | ❌ | ❌ | ❌ |
| User | ✅ own dept | ✅ own dept | ✅ lower rank only | ✅ own dept + outrank | ✅ own dept + outrank |
| Student | ✅ own dept | ✅ own dept | ✅ lower rank only | ✅ own dept + outrank | ✅ own dept + outrank |
| Fee | ✅ own dept | ✅ own dept | ✅ own dept | ✅ own dept | ✅ own dept |
| Invoice | ✅ own dept | ✅ own dept | ✅ own dept | ✅ own dept | ❌ |
| Payment | ✅ own dept | ✅ own dept | ✅ own dept | ✅ own dept | ❌ |
| Scholarship | ✅ | ✅ | ❌ | ❌ | ❌ |
| ScholarshipAward | ✅ own dept | ✅ own dept | ✅ own dept | ✅ own dept | ❌ |
| AuditLog | ❌ | ❌ | ❌ | ❌ | ❌ |

---

## student
Read-only, own data only. No access to admin routes.

| Model | index | show | create | update | delete |
|---|---|---|---|---|---|
| University | ❌ | ✅ own | ❌ | ❌ | ❌ |
| Department | ❌ | ✅ own | ❌ | ❌ | ❌ |
| Fee | ✅ own dept | ✅ own dept | ❌ | ❌ | ❌ |
| Invoice | ✅ own | ✅ own | ❌ | ❌ | ❌ |
| Payment | ✅ own | ✅ own | ❌ | ❌ | ❌ |
| ScholarshipAward | ✅ own | ✅ own | ❌ | ❌ | ❌ |

---

## Role Hierarchy (rank)

| Role | Rank |
|---|---|
| super_admin | 4 |
| university_admin | 3 |
| department_admin | 2 |
| staff_admin | 1 |
| student | 0 |

A user can only create, update, delete, or toggle status of users with a **strictly lower rank** than their own.