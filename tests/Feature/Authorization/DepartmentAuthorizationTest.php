<?php

use App\Models\{User, University, Department};
use Illuminate\Support\Facades\Hash;

// ─── viewAny ────────────────────────────────────────────────────────────────

test('super_admin can view departments list', function () {
    // Arrange
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.index'));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can view departments list', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.index'));

    // Assert
    $response->assertStatus(200);
});

test('department_admin can view departments list', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.index'));

    // Assert
    $response->assertStatus(200);
});

test('staff_admin can view departments list', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.index'));

    // Assert
    $response->assertStatus(200);
});

test('student cannot view departments list', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->get(route('admin.departments.index'));

    // Assert
    $response->assertStatus(403);
});

// ─── view ────────────────────────────────────────────────────────────────────

test('super_admin can view any department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.show', $department));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can view any department', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.show', $department2));

    // Assert
    $response->assertStatus(200);
});

test('department_admin can view any department', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = deptAdmin($department1, $university1);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.show', $department2));

    // Assert
    $response->assertStatus(200);
});

test('staff_admin can view any department', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = staffAdmin($department1, $university1);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.show', $department2));

    // Assert
    $response->assertStatus(200);
});

test('student can view their own department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->get(route('student.department.show'));

    // Assert
    $response->assertStatus(200);
});

test('student cannot access admin department show', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->get(route('admin.departments.show', $department));

    // Assert
    $response->assertStatus(403);
});

// ─── create ──────────────────────────────────────────────────────────────────

test('super_admin can access create department form', function () {
    // Arrange
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.create'));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can access create department form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.create'));

    // Assert
    $response->assertStatus(200);
});

test('department_admin cannot access create department form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.create'));

    // Assert
    $response->assertStatus(403);
});

test('staff_admin cannot access create department form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.create'));

    // Assert
    $response->assertStatus(403);
});

test('student cannot access create department form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->get(route('admin.departments.create'));

    // Assert
    $response->assertStatus(403);
});

test('super_admin can create a department', function () {
    // Arrange
    $admin = superAdmin();
    $university = University::factory()->create();

    // Act
    $response = $this->actingAs($admin)->post(route('admin.departments.store'), [
        'name'          => 'Test Department',
        'university_id' => $university->id,
    ]);

    // Assert
    $response->assertRedirect(route('admin.departments.index'));
    $this->assertDatabaseHas('departments', ['name' => 'Test Department']);
});

test('university_admin can create a department in their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.departments.store'), [
        'name'          => 'New Department',
        'university_id' => $university->id,
    ]);

    // Assert
    $response->assertRedirect(route('admin.departments.index'));
    $this->assertDatabaseHas('departments', ['name' => 'New Department']);
});

test('university_admin cannot create a department in another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.departments.store'), [
        'name'          => 'Hacked Department',
        'university_id' => $university2->id,
    ]);

    // Assert
    $response->assertStatus(403);
});

test('department_admin cannot create a department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.departments.store'), [
        'name'          => 'Hacked Department',
        'university_id' => $university->id,
    ]);

    // Assert
    $response->assertStatus(403);
});

test('staff_admin cannot create a department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.departments.store'), [
        'name'          => 'Hacked Department',
        'university_id' => $university->id,
    ]);

    // Assert
    $response->assertStatus(403);
});

test('student cannot create a department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->post(route('admin.departments.store'), [
        'name'          => 'Hacked Department',
        'university_id' => $university->id,
    ]);

    // Assert
    $response->assertStatus(403);
});

// ─── edit ────────────────────────────────────────────────────────────────────

test('super_admin can access edit department form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.edit', $department));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can access edit form for department in their university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.edit', $department));

    // Assert
    $response->assertStatus(200);
});

test('university_admin cannot access edit form for department in another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.edit', $department2));

    // Assert
    $response->assertStatus(403);
});

test('department_admin can access edit form for their own department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.edit', $department));

    // Assert
    $response->assertStatus(200);
});

test('department_admin cannot access edit form for another department', function () {
    // Arrange
    [$university, $department1] = createUniWithDept();
    $department2 = Department::factory()->create(['university_id' => $university->id]);
    $admin = deptAdmin($department1, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.edit', $department2));

    // Assert
    $response->assertStatus(403);
});

test('staff_admin cannot access edit department form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.departments.edit', $department));

    // Assert
    $response->assertStatus(403);
});

test('student cannot access edit department form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->get(route('admin.departments.edit', $department));

    // Assert
    $response->assertStatus(403);
});

// ─── update ──────────────────────────────────────────────────────────────────

test('super_admin can update any department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->put(route('admin.departments.update', $department), [
        'name'          => 'Updated Department',
        'university_id' => $university->id,
    ]);

    // Assert
    $response->assertRedirect(route('admin.departments.index'));
    $this->assertDatabaseHas('departments', ['name' => 'Updated Department']);
});

test('university_admin can update department in their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.departments.update', $department), [
        'name'          => 'Updated By UniAdmin',
        'university_id' => $university->id,
    ]);

    // Assert
    $response->assertRedirect(route('admin.departments.index'));
    $this->assertDatabaseHas('departments', ['name' => 'Updated By UniAdmin']);
});

test('university_admin cannot update department in another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.departments.update', $department2), [
        'name'          => 'Hacked Department',
        'university_id' => $university2->id,
    ]);

    // Assert
    $response->assertStatus(403);
});

test('department_admin can update their own department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.departments.update', $department), [
        'name'          => 'Updated By DeptAdmin',
        'university_id' => $university->id,
    ]);

    // Assert
    $response->assertRedirect(route('admin.departments.index'));
    $this->assertDatabaseHas('departments', ['name' => 'Updated By DeptAdmin']);
});

test('department_admin cannot update another department', function () {
    // Arrange
    [$university, $department1] = createUniWithDept();
    $department2 = Department::factory()->create(['university_id' => $university->id]);
    $admin = deptAdmin($department1, $university);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.departments.update', $department2), [
        'name'          => 'Hacked Department',
        'university_id' => $university->id,
    ]);

    // Assert
    $response->assertStatus(403);
});

test('staff_admin cannot update a department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.departments.update', $department), [
        'name'          => 'Hacked Department',
        'university_id' => $university->id,
    ]);

    // Assert
    $response->assertStatus(403);
});

test('student cannot update a department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->put(route('admin.departments.update', $department), [
        'name'          => 'Hacked Department',
        'university_id' => $university->id,
    ]);

    // Assert
    $response->assertStatus(403);
});

// ─── delete ──────────────────────────────────────────────────────────────────

test('super_admin can delete a department', function () {
    // Arrange
    $admin = superAdmin();
    $university = University::factory()->create();
    $department = Department::factory()->create(['university_id' => $university->id]);

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.departments.destroy', $department));

    // Assert
    $response->assertRedirect(route('admin.departments.index'));
    $this->assertDatabaseMissing('departments', ['id' => $department->id]);
});

test('university_admin can delete department in their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $newDepartment = Department::factory()->create(['university_id' => $university->id]);

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.departments.destroy', $newDepartment));

    // Assert
    $response->assertRedirect(route('admin.departments.index'));
    $this->assertDatabaseMissing('departments', ['id' => $newDepartment->id]);
});

test('university_admin cannot delete department in another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.departments.destroy', $department2));

    // Assert
    $response->assertStatus(403);
});

test('department_admin cannot delete a department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.departments.destroy', $department));

    // Assert
    $response->assertStatus(403);
});

test('staff_admin cannot delete a department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.departments.destroy', $department));

    // Assert
    $response->assertStatus(403);
});

test('student cannot delete a department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->delete(route('admin.departments.destroy', $department));

    // Assert
    $response->assertStatus(403);
});