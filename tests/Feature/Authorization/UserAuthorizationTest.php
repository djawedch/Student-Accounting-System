<?php

use App\Models\Department;

// ─── viewAny ────────────────────────────────────────────────────────────────

test('super_admin can view users list', function () {
    // Arrange
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can view users list', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    // Assert
    $response->assertStatus(200);
});

test('department_admin can view users list', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    // Assert
    $response->assertStatus(200);
});

test('staff_admin can view users list', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    // Assert
    $response->assertStatus(200);
});

test('student cannot view users list', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->get(route('admin.users.index'));

    // Assert
    $response->assertStatus(403);
});

// ─── view ────────────────────────────────────────────────────────────────────

test('super_admin can view any user', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $target = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.show', $target));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can view user in their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $target = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.show', $target));

    // Assert
    $response->assertStatus(200);
});

test('university_admin cannot view user from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $target = uniAdmin($university2);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.show', $target));

    // Assert
    $response->assertStatus(403);
});

test('department_admin can view user in their own department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $target = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.show', $target));

    // Assert
    $response->assertStatus(200);
});

test('department_admin cannot view user from another department', function () {
    [$university, $department1] = createUniWithDept();
    $department2 = createDepartment($university);
    $admin = deptAdmin($department1, $university);
    $target = deptAdmin($department2, $university);

    $response = $this->actingAs($admin)->get(route('admin.users.show', $target));
    $response->assertStatus(403);
});

test('staff_admin can view user in their own department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);
    $target = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.show', $target));

    // Assert
    $response->assertStatus(200);
});

test('staff_admin cannot view user from another department', function () {
    // Arrange
    [$university, $department1] = createUniWithDept();
    $department2 = createDepartment($university);
    $admin = staffAdmin($department1, $university);
    $target = staffAdmin($department2, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.show', $target));

    // Assert
    $response->assertStatus(403);
});

test('student cannot view any user', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);
    $target = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($student)->get(route('admin.users.show', $target));

    // Assert
    $response->assertStatus(403);
});

// ─── create ──────────────────────────────────────────────────────────────────

test('super_admin can access create user form', function () {
    // Arrange
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.create'));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can access create user form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.create'));

    // Assert
    $response->assertStatus(200);
});

test('department_admin can access create user form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.create'));

    // Assert
    $response->assertStatus(200);
});

test('staff_admin can access create user form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.users.create'));

    // Assert
    $response->assertStatus(200);
});

test('student cannot access create user form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->get(route('admin.users.create'));

    // Assert
    $response->assertStatus(403);
});

test('super_admin can create a user with lower rank', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();

    // Act
    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'first_name'    => 'John',
        'last_name'     => 'Doe',
        'email'         => 'john@test.com',
        'password'      => 'password',
        'password_confirmation' => 'password',
        'date_of_birth' => '1990-01-01',
        'role'          => 'university_admin',
        'university_id' => $university->id,
        'is_active'     => true,
    ]);

    // Assert
    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', ['email' => 'john@test.com']);
});

test('university_admin cannot create a user with equal or higher rank', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'first_name'    => 'John',
        'last_name'     => 'Doe',
        'email'         => 'john@test.com',
        'password'      => 'password',
        'password_confirmation' => 'password',
        'date_of_birth' => '1990-01-01',
        'role'          => 'university_admin',
        'university_id' => $university->id,
        'is_active'     => true,
    ]);

    // Assert
    $response->assertStatus(403);
});

test('department_admin can create a staff_admin in their department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'first_name'    => 'Jane',
        'last_name'     => 'Doe',
        'email'         => 'jane@test.com',
        'password'      => 'password',
        'password_confirmation' => 'password',
        'date_of_birth' => '1995-01-01',
        'role'          => 'staff_admin',
        'university_id' => $university->id,
        'department_id' => $department->id,
        'is_active'     => true,
    ]);

    // Assert
    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', ['email' => 'jane@test.com']);
});

// ─── update ──────────────────────────────────────────────────────────────────

test('super_admin can update any user', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $target = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.users.update', $target), [
        'first_name'    => 'Updated',
        'last_name'     => 'Name',
        'email'         => $target->email,
        'date_of_birth' => '1990-01-01',
        'role'          => 'university_admin',
        'university_id' => $university->id,
        'is_active'     => true,
    ]);

    // Assert
    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', ['first_name' => 'Updated']);
});

test('university_admin cannot update user from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $target = deptAdmin($department2, $university2);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.users.update', $target), [
        'first_name'    => 'Hacked',
        'last_name'     => 'Name',
        'email'         => $target->email,
        'date_of_birth' => '1990-01-01',
        'role'          => 'department_admin',
        'university_id' => $university2->id,
        'department_id' => $department2->id,
        'is_active'     => true,
    ]);

    // Assert
    $response->assertStatus(403);
});

test('user cannot update themselves', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.users.update', $admin), [
        'first_name'    => 'Self',
        'last_name'     => 'Update',
        'email'         => $admin->email,
        'date_of_birth' => '1990-01-01',
        'role'          => 'university_admin',
        'university_id' => $university->id,
        'is_active'     => true,
    ]);

    // Assert
    $response->assertStatus(403);
});

// ─── toggleStatus ────────────────────────────────────────────────────────────

test('super_admin can toggle status of any user', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $target = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $target));

    // Assert
    $response->assertRedirect(route('admin.users.index'));
});

test('university_admin cannot toggle status of user from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $target = deptAdmin($department2, $university2);

    // Act
    $response = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $target));

    // Assert
    $response->assertStatus(403);
});

test('user cannot toggle their own status', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $admin));

    // Assert
    $response->assertStatus(403);
});