<?php

use App\Models\{User, University, Department, Student};
use Illuminate\Support\Facades\Hash;

// ─── viewAny ────────────────────────────────────────────────────────────────

test('super_admin can view students list', function () {
    $response = $this->actingAs(superAdmin())->get(route('admin.students.index'));
    
    $response->assertStatus(200);
});

test('university_admin can view students list', function () {
    [$university, $department] = createUniWithDept();

    $response = $this->actingAs(uniAdmin($university))->get(route('admin.students.index'));

    $response->assertStatus(200);
});

test('department_admin can view students list', function () {
    [$university, $department] = createUniWithDept();

    $response = $this->actingAs(deptAdmin($department, $university))->get(route('admin.students.index'));

    $response->assertStatus(200);
});

test('staff_admin can view students list', function () {
    [$university, $department] = createUniWithDept();

    $response = $this->actingAs(staffAdmin($department, $university))->get(route('admin.students.index'));

    $response->assertStatus(200);
});

test('student cannot view students list', function () {
    [$university, $department] = createUniWithDept();

    $response = $this->actingAs(studentUser($department, $university))->get(route('admin.students.index'));

    $response->assertStatus(403);
});

// ─── view ────────────────────────────────────────────────────────────────────

test('super_admin can view any student', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.students.show', $student));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can view student in their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.students.show', $student));

    // Assert
    $response->assertStatus(200);
});

test('university_admin cannot view student from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $studentUser = studentUser($department2, $university2);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.students.show', $studentUser));

    // Assert
    $response->assertStatus(403);
});

test('department_admin can view student in their own department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.students.show', $student));

    // Assert
    $response->assertStatus(200);
});

test('department_admin cannot view student from another department', function () {
    // Arrange
    [$university, $department1] = createUniWithDept();
    $department2 = Department::factory()->create(['university_id' => $university->id]);
    $admin = deptAdmin($department1, $university);
    $studentUser = studentUser($department2, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.students.show', $studentUser));

    // Assert
    $response->assertStatus(403);
});

// ─── create ──────────────────────────────────────────────────────────────────

test('super_admin can access create student form', function () {
    $response = $this->actingAs(superAdmin())->get(route('admin.students.create'));
    $response->assertStatus(200);
});

test('university_admin can access create student form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(uniAdmin($university))->get(route('admin.students.create'));
    $response->assertStatus(200);
});

test('department_admin can access create student form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(deptAdmin($department, $university))->get(route('admin.students.create'));
    $response->assertStatus(200);
});

test('staff_admin can access create student form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(staffAdmin($department, $university))->get(route('admin.students.create'));
    $response->assertStatus(200);
});

test('student cannot access create student form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(studentUser($department, $university))->get(route('admin.students.create'));
    $response->assertStatus(403);
});

// ─── toggleStatus ────────────────────────────────────────────────────────────

test('super_admin can toggle student status', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);

    // Act
    $response = $this->actingAs($admin)->patch(route('admin.students.toggle-status', $studentUser));

    // Assert
    $response->assertRedirect(route('admin.students.index'));
});

test('university_admin cannot toggle status of student from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $studentUser = studentUser($department2, $university2);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);

    // Act
    $response = $this->actingAs($admin)->patch(route('admin.students.toggle-status', $studentUser));

    // Assert
    $response->assertStatus(403);
});