<?php

use App\Models\{Department, Fee, Student};

// ─── viewAny ────────────────────────────────────────────────────────────────

test('super_admin can view fees list', function () {
    $response = $this->actingAs(superAdmin())->get(route('admin.fees.index'));
    $response->assertStatus(200);
});

test('university_admin can view fees list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(uniAdmin($university))->get(route('admin.fees.index'));
    $response->assertStatus(200);
});

test('department_admin can view fees list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(deptAdmin($department, $university))->get(route('admin.fees.index'));
    $response->assertStatus(200);
});

test('staff_admin can view fees list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(staffAdmin($department, $university))->get(route('admin.fees.index'));
    $response->assertStatus(200);
});

test('student cannot view fees list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(studentUser($department, $university))->get(route('admin.fees.index'));
    $response->assertStatus(403);
});

// ─── view ────────────────────────────────────────────────────────────────────

test('super_admin can view any fee', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $fee = Fee::factory()->create(['department_id' => $department->id]);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.fees.show', $fee));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can view fee in their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $fee = Fee::factory()->create(['department_id' => $department->id]);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.fees.show', $fee));

    // Assert
    $response->assertStatus(200);
});

test('university_admin cannot view fee from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $fee = Fee::factory()->create(['department_id' => $department2->id]);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.fees.show', $fee));

    // Assert
    $response->assertStatus(403);
});

test('department_admin can view fee in their own department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $fee = Fee::factory()->create(['department_id' => $department->id]);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.fees.show', $fee));

    // Assert
    $response->assertStatus(200);
});

test('department_admin cannot view fee from another department', function () {
    // Arrange
    [$university, $department1] = createUniWithDept();
    $department2 = createDepartment($university);
    $admin = deptAdmin($department1, $university);
    $fee = Fee::factory()->create(['department_id' => $department2->id]);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.fees.show', $fee));

    // Assert
    $response->assertStatus(403);
});

test('student can view fees of their own department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);
    $fee = Fee::factory()->create(['department_id' => $department->id]);

    // Act
    $response = $this->actingAs($student)->get(route('student.fees.show', $fee));

    // Assert
    $response->assertStatus(200);
});

test('student cannot view fee from another department', function () {
    // Arrange
    [$university, $department1] = createUniWithDept();
    $department2 = createDepartment($university);
    $studentUser = studentUser($department1, $university);
    Student::factory()->create(['user_id' => $studentUser->id]);
    $fee = Fee::factory()->create(['department_id' => $department2->id]);

    // Act
    $response = $this->actingAs($studentUser)->get(route('admin.fees.show', $fee));

    // Assert
    $response->assertStatus(403);
});

// ─── create ──────────────────────────────────────────────────────────────────

test('super_admin can access create fee form', function () {
    $response = $this->actingAs(superAdmin())->get(route('admin.fees.create'));
    $response->assertStatus(200);
});

test('university_admin can access create fee form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(uniAdmin($university))->get(route('admin.fees.create'));
    $response->assertStatus(200);
});

test('department_admin can access create fee form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(deptAdmin($department, $university))->get(route('admin.fees.create'));
    $response->assertStatus(200);
});

test('staff_admin can access create fee form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(staffAdmin($department, $university))->get(route('admin.fees.create'));
    $response->assertStatus(200);
});

test('student cannot access create fee form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(studentUser($department, $university))->get(route('admin.fees.create'));
    $response->assertStatus(403);
});

test('super_admin can create a fee', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();

    // Act
    $response = $this->actingAs($admin)->post(route('admin.fees.store'), [
        'name'          => 'Test Fee',
        'department_id' => $department->id,
        'amount'        => 10000,
        'academic_year' => '2024-2025',
        'description'   => null,
    ]);

    // Assert
    $response->assertRedirect(route('admin.fees.index'));
    $this->assertDatabaseHas('fees', ['name' => 'Test Fee']);
});

test('university_admin can create a fee in their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.fees.store'), [
        'name'          => 'Uni Fee',
        'department_id' => $department->id,
        'amount'        => 5000,
        'academic_year' => '2024-2025',
    ]);

    // Assert
    $response->assertRedirect(route('admin.fees.index'));
    $this->assertDatabaseHas('fees', ['name' => 'Uni Fee']);
});

test('university_admin cannot create a fee in another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.fees.store'), [
        'name'          => 'Hacked Fee',
        'department_id' => $department2->id,
        'amount'        => 5000,
        'academic_year' => '2024-2025',
    ]);

    // Assert
    $response->assertStatus(403);
});

test('department_admin can create a fee in their own department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.fees.store'), [
        'name'          => 'Dept Fee',
        'department_id' => $department->id,
        'amount'        => 3000,
        'academic_year' => '2024-2025',
    ]);

    // Assert
    $response->assertRedirect(route('admin.fees.index'));
    $this->assertDatabaseHas('fees', ['name' => 'Dept Fee']);
});

test('department_admin cannot create a fee in another department', function () {
    // Arrange
    [$university, $department1] = createUniWithDept();
    $department2 = createDepartment($university);
    $admin = deptAdmin($department1, $university);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.fees.store'), [
        'name'          => 'Hacked Fee',
        'department_id' => $department2->id,
        'amount'        => 3000,
        'academic_year' => '2024-2025',
    ]);

    // Assert
    $response->assertStatus(403);
});

// ─── update ──────────────────────────────────────────────────────────────────

test('super_admin can update any fee', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $fee = Fee::factory()->create(['department_id' => $department->id]);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.fees.update', $fee), [
        'name'          => 'Updated Fee',
        'department_id' => $department->id,
        'amount'        => 15000,
        'academic_year' => '2024-2025',
    ]);

    // Assert
    $response->assertRedirect(route('admin.fees.index'));
    $this->assertDatabaseHas('fees', ['name' => 'Updated Fee']);
});

test('university_admin can update fee in their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $fee = Fee::factory()->create(['department_id' => $department->id]);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.fees.update', $fee), [
        'name'          => 'Updated Uni Fee',
        'department_id' => $department->id,
        'amount'        => 8000,
        'academic_year' => '2024-2025',
    ]);

    // Assert
    $response->assertRedirect(route('admin.fees.index'));
    $this->assertDatabaseHas('fees', ['name' => 'Updated Uni Fee']);
});

test('university_admin cannot update fee from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $fee = Fee::factory()->create(['department_id' => $department2->id]);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.fees.update', $fee), [
        'name'          => 'Hacked Fee',
        'department_id' => $department2->id,
        'amount'        => 8000,
        'academic_year' => '2024-2025',
    ]);

    // Assert
    $response->assertStatus(403);
});

test('department_admin can update fee in their own department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $fee = Fee::factory()->create(['department_id' => $department->id]);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.fees.update', $fee), [
        'name'          => 'Updated Dept Fee',
        'department_id' => $department->id,
        'amount'        => 4000,
        'academic_year' => '2024-2025',
    ]);

    // Assert
    $response->assertRedirect(route('admin.fees.index'));
    $this->assertDatabaseHas('fees', ['name' => 'Updated Dept Fee']);
});

test('department_admin cannot update fee from another department', function () {
    // Arrange
    [$university, $department1] = createUniWithDept();
    $department2 = createDepartment($university);
    $admin = deptAdmin($department1, $university);
    $fee = Fee::factory()->create(['department_id' => $department2->id]);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.fees.update', $fee), [
        'name'          => 'Hacked Fee',
        'department_id' => $department2->id,
        'amount'        => 4000,
        'academic_year' => '2024-2025',
    ]);

    // Assert
    $response->assertStatus(403);
});

test('student cannot update a fee', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);
    $fee = Fee::factory()->create(['department_id' => $department->id]);

    // Act
    $response = $this->actingAs($student)->put(route('admin.fees.update', $fee), [
        'name'          => 'Hacked Fee',
        'department_id' => $department->id,
        'amount'        => 4000,
        'academic_year' => '2024-2025',
    ]);

    // Assert
    $response->assertStatus(403);
});

// ─── delete ──────────────────────────────────────────────────────────────────

test('super_admin can delete a fee', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $fee = Fee::factory()->create(['department_id' => $department->id]);

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.fees.destroy', $fee));

    // Assert
    $response->assertRedirect(route('admin.fees.index'));
    $this->assertDatabaseMissing('fees', ['id' => $fee->id]);
});

test('university_admin can delete fee in their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $fee = Fee::factory()->create(['department_id' => $department->id]);

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.fees.destroy', $fee));

    // Assert
    $response->assertRedirect(route('admin.fees.index'));
    $this->assertDatabaseMissing('fees', ['id' => $fee->id]);
});

test('university_admin cannot delete fee from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $fee = Fee::factory()->create(['department_id' => $department2->id]);

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.fees.destroy', $fee));

    // Assert
    $response->assertStatus(403);
});

test('department_admin can delete fee in their own department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $fee = Fee::factory()->create(['department_id' => $department->id]);

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.fees.destroy', $fee));

    // Assert
    $response->assertRedirect(route('admin.fees.index'));
    $this->assertDatabaseMissing('fees', ['id' => $fee->id]);
});

test('department_admin cannot delete fee from another department', function () {
    // Arrange
    [$university, $department1] = createUniWithDept();
    $department2 = createDepartment($university);
    $admin = deptAdmin($department1, $university);
    $fee = Fee::factory()->create(['department_id' => $department2->id]);

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.fees.destroy', $fee));

    // Assert
    $response->assertStatus(403);
});

test('student cannot delete a fee', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);
    $fee = Fee::factory()->create(['department_id' => $department->id]);

    // Act
    $response = $this->actingAs($student)->delete(route('admin.fees.destroy', $fee));

    // Assert
    $response->assertStatus(403);
});