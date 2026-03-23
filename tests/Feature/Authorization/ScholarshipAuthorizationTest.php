<?php

use App\Models\Scholarship;

// ─── viewAny ────────────────────────────────────────────────────────────────

test('super_admin can view scholarships list', function () {
    $response = $this->actingAs(superAdmin())->get(route('admin.scholarships.index'));
    $response->assertStatus(200);
});

test('university_admin can view scholarships list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(uniAdmin($university))->get(route('admin.scholarships.index'));
    $response->assertStatus(200);
});

test('department_admin can view scholarships list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(deptAdmin($department, $university))->get(route('admin.scholarships.index'));
    $response->assertStatus(200);
});

test('staff_admin can view scholarships list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(staffAdmin($department, $university))->get(route('admin.scholarships.index'));
    $response->assertStatus(200);
});

test('student cannot view scholarships list via admin', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(studentUser($department, $university))->get(route('admin.scholarships.index'));
    $response->assertStatus(403);
});

// ─── view ────────────────────────────────────────────────────────────────────

test('super_admin can view any scholarship', function () {
    // Arrange
    $admin = superAdmin();
    $scholarship = Scholarship::factory()->create();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.scholarships.show', $scholarship));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can view any scholarship', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $scholarship = Scholarship::factory()->create();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.scholarships.show', $scholarship));

    // Assert
    $response->assertStatus(200);
});

test('department_admin can view any scholarship', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $scholarship = Scholarship::factory()->create();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.scholarships.show', $scholarship));

    // Assert
    $response->assertStatus(200);
});

test('staff_admin can view any scholarship', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);
    $scholarship = Scholarship::factory()->create();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.scholarships.show', $scholarship));

    // Assert
    $response->assertStatus(200);
});

test('student cannot view scholarship via admin route', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);
    $scholarship = Scholarship::factory()->create();

    // Act
    $response = $this->actingAs($student)->get(route('admin.scholarships.show', $scholarship));

    // Assert
    $response->assertStatus(403);
});

// ─── create ──────────────────────────────────────────────────────────────────

test('super_admin can access create scholarship form', function () {
    $response = $this->actingAs(superAdmin())->get(route('admin.scholarships.create'));
    $response->assertStatus(200);
});

test('university_admin cannot access create scholarship form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(uniAdmin($university))->get(route('admin.scholarships.create'));
    $response->assertStatus(403);
});

test('department_admin cannot access create scholarship form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(deptAdmin($department, $university))->get(route('admin.scholarships.create'));
    $response->assertStatus(403);
});

test('staff_admin cannot access create scholarship form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(staffAdmin($department, $university))->get(route('admin.scholarships.create'));
    $response->assertStatus(403);
});

test('student cannot access create scholarship form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(studentUser($department, $university))->get(route('admin.scholarships.create'));
    $response->assertStatus(403);
});

test('super_admin can create a scholarship', function () {
    // Arrange
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->post(route('admin.scholarships.store'), [
        'name'        => 'Test Scholarship',
        'amount'      => 50000,
        'description' => 'Test description',
    ]);

    // Assert
    $response->assertRedirect(route('admin.scholarships.index'));
    $this->assertDatabaseHas('scholarships', ['name' => 'Test Scholarship']);
});

test('university_admin cannot create a scholarship', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.scholarships.store'), [
        'name'   => 'Hacked Scholarship',
        'amount' => 50000,
    ]);

    // Assert
    $response->assertStatus(403);
});

// ─── update ──────────────────────────────────────────────────────────────────

test('super_admin can update any scholarship', function () {
    // Arrange
    $admin = superAdmin();
    $scholarship = Scholarship::factory()->create();

    // Act
    $response = $this->actingAs($admin)->put(route('admin.scholarships.update', $scholarship), [
        'name'   => 'Updated Scholarship',
        'amount' => 60000,
    ]);

    // Assert
    $response->assertRedirect(route('admin.scholarships.index'));
    $this->assertDatabaseHas('scholarships', ['name' => 'Updated Scholarship']);
});

test('university_admin cannot update a scholarship', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $scholarship = Scholarship::factory()->create();

    // Act
    $response = $this->actingAs($admin)->put(route('admin.scholarships.update', $scholarship), [
        'name'   => 'Hacked Scholarship',
        'amount' => 60000,
    ]);

    // Assert
    $response->assertStatus(403);
});

test('department_admin cannot update a scholarship', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $scholarship = Scholarship::factory()->create();

    // Act
    $response = $this->actingAs($admin)->put(route('admin.scholarships.update', $scholarship), [
        'name'   => 'Hacked Scholarship',
        'amount' => 60000,
    ]);

    // Assert
    $response->assertStatus(403);
});

test('student cannot update a scholarship', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);
    $scholarship = Scholarship::factory()->create();

    // Act
    $response = $this->actingAs($student)->put(route('admin.scholarships.update', $scholarship), [
        'name'   => 'Hacked Scholarship',
        'amount' => 60000,
    ]);

    // Assert
    $response->assertStatus(403);
});

// ─── delete ──────────────────────────────────────────────────────────────────

test('super_admin can delete a scholarship', function () {
    // Arrange
    $admin = superAdmin();
    $scholarship = Scholarship::factory()->create();

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.scholarships.destroy', $scholarship));

    // Assert
    $response->assertRedirect(route('admin.scholarships.index'));
    $this->assertDatabaseMissing('scholarships', ['id' => $scholarship->id]);
});

test('university_admin cannot delete a scholarship', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $scholarship = Scholarship::factory()->create();

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.scholarships.destroy', $scholarship));

    // Assert
    $response->assertStatus(403);
});

test('department_admin cannot delete a scholarship', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $scholarship = Scholarship::factory()->create();

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.scholarships.destroy', $scholarship));

    // Assert
    $response->assertStatus(403);
});

test('student cannot delete a scholarship', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);
    $scholarship = Scholarship::factory()->create();

    // Act
    $response = $this->actingAs($student)->delete(route('admin.scholarships.destroy', $scholarship));

    // Assert
    $response->assertStatus(403);
});