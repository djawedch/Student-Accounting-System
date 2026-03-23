<?php

use App\Models\{User, University, Department};
use Illuminate\Support\Facades\Hash;

// ─── Helpers ────────────────────────────────────────────────────────────────

function createUniWithDept(): array
{
    $university = University::factory()->create();
    $department = createDepartment($university);
    return [$university, $department];
}

function createDepartment(University $university, ?string $name = null): Department
{
    return Department::factory()->create([
        'university_id' => $university->id,
        'name' => $name ?? uniqid('dept_'),
    ]);
}

function superAdmin(): User
{
    return User::factory()->superAdmin()->create(['password' => Hash::make('password')]);
}

function uniAdmin(University $university): User
{
    return User::factory()->universityAdmin($university->id)->create(['password' => Hash::make('password')]);
}

function deptAdmin(Department $department, University $university): User
{
    return User::factory()->departmentAdmin($department->id, $university->id)->create(['password' => Hash::make('password')]);
}

function staffAdmin(Department $department, University $university): User
{
    return User::factory()->staffAdmin($department->id, $university->id)->create(['password' => Hash::make('password')]);
}

function studentUser(Department $department, University $university): User
{
    return User::factory()->student($department->id, $university->id)->create(['password' => Hash::make('password')]);
}

// ─── viewAny ────────────────────────────────────────────────────────────────

test('super_admin can view universities list', function () {
    // Arrange
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.index'));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can view universities list', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.index'));

    // Assert
    $response->assertStatus(200);
});

test('department_admin can view universities list', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.index'));

    // Assert
    $response->assertStatus(200);
});

test('staff_admin can view universities list', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.index'));

    // Assert
    $response->assertStatus(200);
});

test('student cannot view universities list', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->get(route('admin.universities.index'));

    // Assert
    $response->assertStatus(403);
});

// ─── view ────────────────────────────────────────────────────────────────────

test('super_admin can view any university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.show', $university));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can view any university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.show', $university2));

    // Assert
    $response->assertStatus(200);
});

test('department_admin can view any university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = deptAdmin($department1, $university1);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.show', $university2));

    // Assert
    $response->assertStatus(200);
});

test('staff_admin can view any university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = staffAdmin($department1, $university1);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.show', $university2));

    // Assert
    $response->assertStatus(200);
});

test('student can view their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->get(route('student.university.show'));

    // Assert
    $response->assertStatus(200);
});

test('student cannot access admin university show', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->get(route('admin.universities.show', $university));

    // Assert
    $response->assertStatus(403);
});

// ─── create ──────────────────────────────────────────────────────────────────

test('super_admin can access create university form', function () {
    // Arrange
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.create'));

    // Assert
    $response->assertStatus(200);
});

test('university_admin cannot access create university form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.create'));

    // Assert
    $response->assertStatus(403);
});

test('department_admin cannot access create university form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.create'));

    // Assert
    $response->assertStatus(403);
});

test('staff_admin cannot access create university form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.create'));

    // Assert
    $response->assertStatus(403);
});

test('student cannot access create university form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->get(route('admin.universities.create'));

    // Assert
    $response->assertStatus(403);
});

test('super_admin can create a university', function () {
    // Arrange
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->post(route('admin.universities.store'), [
        'name' => 'Test University',
        'city' => 'Oran',
    ]);

    // Assert
    $response->assertRedirect(route('admin.universities.index'));
    $this->assertDatabaseHas('universities', ['name' => 'Test University']);
});

test('university_admin cannot create a university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.universities.store'), [
        'name' => 'Hacked University',
        'city' => 'Oran',
    ]);

    // Assert
    $response->assertStatus(403);
});

test('department_admin cannot create a university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.universities.store'), [
        'name' => 'Hacked University',
        'city' => 'Oran',
    ]);

    // Assert
    $response->assertStatus(403);
});

test('staff_admin cannot create a university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.universities.store'), [
        'name' => 'Hacked University',
        'city' => 'Oran',
    ]);

    // Assert
    $response->assertStatus(403);
});

test('student cannot create a university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->post(route('admin.universities.store'), [
        'name' => 'Hacked University',
        'city' => 'Oran',
    ]);

    // Assert
    $response->assertStatus(403);
});


// ─── update ──────────────────────────────────────────────────────────────────

test('super_admin can access edit university form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.edit', $university));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can access edit form for their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.edit', $university));

    // Assert
    $response->assertStatus(200);
});

test('university_admin cannot access edit form for another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.edit', $university2));

    // Assert
    $response->assertStatus(403);
});

test('department_admin cannot access edit university form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.edit', $university));

    // Assert
    $response->assertStatus(403);
});

test('staff_admin cannot access edit university form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.universities.edit', $university));

    // Assert
    $response->assertStatus(403);
});

test('student cannot access edit university form', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->get(route('admin.universities.edit', $university));

    // Assert
    $response->assertStatus(403);
});

test('super_admin can update any university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = superAdmin();

    // Act
    $response = $this->actingAs($admin)->put(route('admin.universities.update', $university), [
        'name' => 'Updated University',
        'city' => 'Alger',
    ]);

    // Assert
    $response->assertRedirect(route('admin.universities.index'));
    $this->assertDatabaseHas('universities', ['name' => 'Updated University']);
});

test('university_admin can update their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.universities.update', $university), [
        'name' => 'Updated By Admin',
        'city' => 'Oran',
    ]);

    // Assert
    $response->assertRedirect(route('admin.universities.index'));
    $this->assertDatabaseHas('universities', ['name' => 'Updated By Admin']);
});

test('university_admin cannot update another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.universities.update', $university2), [
        'name' => 'Hacked University',
        'city' => 'Oran',
    ]);

    // Assert
    $response->assertStatus(403);
});

test('department_admin cannot update a university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.universities.update', $university), [
        'name' => 'Hacked University',
        'city' => 'Oran',
    ]);

    // Assert
    $response->assertStatus(403);
});

test('staff_admin cannot update a university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.universities.update', $university), [
        'name' => 'Hacked University',
        'city' => 'Oran',
    ]);

    // Assert
    $response->assertStatus(403);
});

test('student cannot update a university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->put(route('admin.universities.update', $university), [
        'name' => 'Hacked University',
        'city' => 'Oran',
    ]);

    // Assert
    $response->assertStatus(403);
});

// ─── delete ──────────────────────────────────────────────────────────────────

test('super_admin can delete a university', function () {
    // Arrange
    $admin = superAdmin();
    $university = University::factory()->create();

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.universities.destroy', $university));

    // Assert
    $response->assertRedirect(route('admin.universities.index'));
    $this->assertDatabaseMissing('universities', ['id' => $university->id]);
});

test('university_admin cannot delete a university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.universities.destroy', $university));

    // Assert
    $response->assertStatus(403);
});

test('department_admin cannot delete a university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.universities.destroy', $university));

    // Assert
    $response->assertStatus(403);
});

test('staff_admin cannot delete a university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.universities.destroy', $university));

    // Assert
    $response->assertStatus(403);
});

test('student cannot delete a university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);

    // Act
    $response = $this->actingAs($student)->delete(route('admin.universities.destroy', $university));

    // Assert
    $response->assertStatus(403);
});