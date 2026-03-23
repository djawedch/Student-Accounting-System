<?php

use App\Models\{University, Department, AuditLog, User};

// ─── Helpers ────────────────────────────────────────────────────────────────

function createAuditLogForUser(User $user): AuditLog
{
    return AuditLog::factory()->create(['user_id' => $user->id]);
}

function createAuditLogForUniversity(University $university): AuditLog
{
    $user = User::factory()->create([
        'university_id' => $university->id,
        'role' => 'university_admin',
    ]);
    return AuditLog::factory()->create(['user_id' => $user->id]);
}

// ─── viewAny (index) ─────────────────────────────────────────────────────────

test('super_admin can view audit logs list', function () {
    $response = $this->actingAs(superAdmin())->get(route('admin.audit-logs.index'));
    $response->assertStatus(200);
});

test('university_admin can view audit logs list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(uniAdmin($university))->get(route('admin.audit-logs.index'));
    $response->assertStatus(200);
});

test('department_admin cannot view audit logs list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(deptAdmin($department, $university))->get(route('admin.audit-logs.index'));
    $response->assertStatus(403);
});

test('staff_admin cannot view audit logs list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(staffAdmin($department, $university))->get(route('admin.audit-logs.index'));
    $response->assertStatus(403);
});

test('student cannot view audit logs list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(studentUser($department, $university))->get(route('admin.audit-logs.index'));
    $response->assertStatus(403);
});

// ─── view (show) ─────────────────────────────────────────────────────────────

test('super_admin can view any audit log', function () {
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $auditLog = createAuditLogForUniversity($university);

    $response = $this->actingAs($admin)->get(route('admin.audit-logs.show', $auditLog));
    $response->assertStatus(200);
});

test('university_admin can view audit log from their own university', function () {
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $auditLog = createAuditLogForUniversity($university);

    $response = $this->actingAs($admin)->get(route('admin.audit-logs.show', $auditLog));
    $response->assertStatus(200);
});

test('university_admin cannot view audit log from another university', function () {
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $auditLog = createAuditLogForUniversity($university2);

    $response = $this->actingAs($admin)->get(route('admin.audit-logs.show', $auditLog));
    $response->assertStatus(403);
});

test('department_admin cannot view any audit log', function () {
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $auditLog = createAuditLogForUniversity($university);

    $response = $this->actingAs($admin)->get(route('admin.audit-logs.show', $auditLog));
    $response->assertStatus(403);
});

test('staff_admin cannot view any audit log', function () {
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);
    $auditLog = createAuditLogForUniversity($university);

    $response = $this->actingAs($admin)->get(route('admin.audit-logs.show', $auditLog));
    $response->assertStatus(403);
});

test('student cannot view any audit log', function () {
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);
    $auditLog = createAuditLogForUniversity($university);

    $response = $this->actingAs($student)->get(route('admin.audit-logs.show', $auditLog));
    $response->assertStatus(403);
});