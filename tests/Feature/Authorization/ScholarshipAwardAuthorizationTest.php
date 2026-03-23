<?php

use App\Models\{University, Department, Scholarship, ScholarshipAward, Student};

// ─── Helpers ────────────────────────────────────────────────────────────────

function createScholarshipAward(Department $department, University $university): ScholarshipAward
{
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $scholarship = Scholarship::factory()->create();
    return ScholarshipAward::factory()->create([
        'student_id' => $student->id,
        'scholarship_id' => $scholarship->id,
    ]);
}

// ─── viewAny ────────────────────────────────────────────────────────────────

test('super_admin can view scholarship awards list', function () {
    $response = $this->actingAs(superAdmin())->get(route('admin.scholarship-awards.index'));
    $response->assertStatus(200);
});

test('university_admin can view scholarship awards list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(uniAdmin($university))->get(route('admin.scholarship-awards.index'));
    $response->assertStatus(200);
});

test('department_admin can view scholarship awards list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(deptAdmin($department, $university))->get(route('admin.scholarship-awards.index'));
    $response->assertStatus(200);
});

test('staff_admin can view scholarship awards list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(staffAdmin($department, $university))->get(route('admin.scholarship-awards.index'));
    $response->assertStatus(200);
});

test('student cannot view scholarship awards list via admin', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(studentUser($department, $university))->get(route('admin.scholarship-awards.index'));
    $response->assertStatus(403);
});

// ─── view ────────────────────────────────────────────────────────────────────

test('super_admin can view any scholarship award', function () {
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $award = createScholarshipAward($department, $university);

    $response = $this->actingAs($admin)->get(route('admin.scholarship-awards.show', $award));
    $response->assertStatus(200);
});

test('university_admin can view scholarship award in their own university', function () {
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $award = createScholarshipAward($department, $university);

    $response = $this->actingAs($admin)->get(route('admin.scholarship-awards.show', $award));
    $response->assertStatus(200);
});

test('university_admin cannot view scholarship award from another university', function () {
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $award = createScholarshipAward($department2, $university2);

    $response = $this->actingAs($admin)->get(route('admin.scholarship-awards.show', $award));
    $response->assertStatus(403);
});

test('department_admin can view scholarship award in their own department', function () {
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $award = createScholarshipAward($department, $university);

    $response = $this->actingAs($admin)->get(route('admin.scholarship-awards.show', $award));
    $response->assertStatus(200);
});

test('department_admin cannot view scholarship award from another department', function () {
    [$university, $department1] = createUniWithDept();
    $department2 = createDepartment($university);
    $admin = deptAdmin($department1, $university);
    $award = createScholarshipAward($department2, $university);

    $response = $this->actingAs($admin)->get(route('admin.scholarship-awards.show', $award));
    $response->assertStatus(403);
});

test('staff_admin can view scholarship award in their own department', function () {
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);
    $award = createScholarshipAward($department, $university);

    $response = $this->actingAs($admin)->get(route('admin.scholarship-awards.show', $award));
    $response->assertStatus(200);
});

test('staff_admin cannot view scholarship award from another department', function () {
    [$university, $department1] = createUniWithDept();
    $department2 = createDepartment($university);
    $admin = staffAdmin($department1, $university);
    $award = createScholarshipAward($department2, $university);

    $response = $this->actingAs($admin)->get(route('admin.scholarship-awards.show', $award));
    $response->assertStatus(403);
});

test('student can view their own scholarship award via student route', function () {
    [$university, $department] = createUniWithDept();
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $scholarship = Scholarship::factory()->create();
    $award = ScholarshipAward::factory()->create([
        'student_id' => $student->id,
        'scholarship_id' => $scholarship->id,
    ]);

    $response = $this->actingAs($studentUser)->get(route('student.scholarship-awards.show', $award));
    $response->assertStatus(200);
});

test('student cannot view another student scholarship award', function () {
    [$university, $department] = createUniWithDept();
    $studentUser1 = studentUser($department, $university);
    $student1 = Student::factory()->create(['user_id' => $studentUser1->id]);
    $studentUser2 = studentUser($department, $university);
    $student2 = Student::factory()->create(['user_id' => $studentUser2->id]);
    $scholarship = Scholarship::factory()->create();
    $award = ScholarshipAward::factory()->create([
        'student_id' => $student2->id,
        'scholarship_id' => $scholarship->id,
    ]);

    $response = $this->actingAs($studentUser1)->get(route('student.scholarship-awards.show', $award));
    $response->assertStatus(403);
});

// ─── create ──────────────────────────────────────────────────────────────────

test('super_admin can access create scholarship award form', function () {
    $response = $this->actingAs(superAdmin())->get(route('admin.scholarship-awards.create'));
    $response->assertStatus(200);
});

test('university_admin can access create scholarship award form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(uniAdmin($university))->get(route('admin.scholarship-awards.create'));
    $response->assertStatus(200);
});

test('department_admin can access create scholarship award form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(deptAdmin($department, $university))->get(route('admin.scholarship-awards.create'));
    $response->assertStatus(200);
});

test('staff_admin can access create scholarship award form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(staffAdmin($department, $university))->get(route('admin.scholarship-awards.create'));
    $response->assertStatus(200);
});

test('student cannot access create scholarship award form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(studentUser($department, $university))->get(route('admin.scholarship-awards.create'));
    $response->assertStatus(403);
});

test('super_admin can create a scholarship award', function () {
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $scholarship = Scholarship::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.scholarship-awards.store'), [
        'student_ids' => [$student->id],
        'scholarship_ids' => [$scholarship->id],
        'grant_date' => '2024-01-01',
        'end_date' => '2024-12-31',
        'status' => 'awarded',
        'reference' => 'REF-001',
    ]);

    $response->assertRedirect(route('admin.scholarship-awards.index'));
    $this->assertDatabaseHas('scholarship_awards', ['reference' => 'REF-001']);
});

test('university_admin can create scholarship award in their own university', function () {
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $scholarship = Scholarship::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.scholarship-awards.store'), [
        'student_ids' => [$student->id],
        'scholarship_ids' => [$scholarship->id],
        'grant_date' => '2024-01-01',
        'end_date' => '2024-12-31',
        'status' => 'awarded',
    ]);

    $response->assertRedirect(route('admin.scholarship-awards.index'));
});

test('university_admin cannot create scholarship award for student from another university', function () {
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $studentUser = studentUser($department2, $university2);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $scholarship = Scholarship::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.scholarship-awards.store'), [
        'student_ids' => [$student->id],
        'scholarship_ids' => [$scholarship->id],
        'grant_date' => '2024-01-01',
        'end_date' => '2024-12-31',
        'status' => 'awarded',
    ]);

    $response->assertStatus(403);
});

test('department_admin can create scholarship award in their own department', function () {
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $scholarship = Scholarship::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.scholarship-awards.store'), [
        'student_ids' => [$student->id],
        'scholarship_ids' => [$scholarship->id],
        'grant_date' => '2024-01-01',
        'end_date' => '2024-12-31',
        'status' => 'awarded',
    ]);

    $response->assertRedirect(route('admin.scholarship-awards.index'));
});

test('department_admin cannot create scholarship award for student from another department', function () {
    [$university, $department1] = createUniWithDept();
    $department2 = createDepartment($university);
    $admin = deptAdmin($department1, $university);
    $studentUser = studentUser($department2, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $scholarship = Scholarship::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.scholarship-awards.store'), [
        'student_ids' => [$student->id],
        'scholarship_ids' => [$scholarship->id],
        'grant_date' => '2024-01-01',
        'end_date' => '2024-12-31',
        'status' => 'awarded',
    ]);

    $response->assertStatus(403);
});

// ─── update ──────────────────────────────────────────────────────────────────

test('super_admin can update any scholarship award', function () {
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $award = createScholarshipAward($department, $university);

    $response = $this->actingAs($admin)->put(route('admin.scholarship-awards.update', $award), [
        'student_id' => $award->student_id,
        'scholarship_id' => $award->scholarship_id,
        'status' => 'paid',
        'grant_date' => '2024-01-01',
        'end_date' => '2024-12-31',
        'paid_at' => '2024-06-01',
    ]);

    $response->assertRedirect(route('admin.scholarship-awards.index'));
    $this->assertDatabaseHas('scholarship_awards', ['id' => $award->id, 'status' => 'paid']);
});

test('university_admin can update scholarship award in their own university', function () {
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $award = createScholarshipAward($department, $university);

    $response = $this->actingAs($admin)->put(route('admin.scholarship-awards.update', $award), [
        'student_id' => $award->student_id,
        'scholarship_id' => $award->scholarship_id,
        'status' => 'paid',
        'grant_date' => '2024-01-01',
        'end_date' => '2024-12-31',
    ]);

    $response->assertRedirect(route('admin.scholarship-awards.index'));
});

test('university_admin cannot update scholarship award from another university', function () {
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $award = createScholarshipAward($department2, $university2);

    $response = $this->actingAs($admin)->put(route('admin.scholarship-awards.update', $award), [
        'student_id' => $award->student_id,
        'scholarship_id' => $award->scholarship_id,
        'status' => 'paid',
        'grant_date' => '2024-01-01',
        'end_date' => '2024-12-31',
    ]);

    $response->assertStatus(403);
});

test('department_admin can update scholarship award in their own department', function () {
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $award = createScholarshipAward($department, $university);

    $response = $this->actingAs($admin)->put(route('admin.scholarship-awards.update', $award), [
        'student_id' => $award->student_id,
        'scholarship_id' => $award->scholarship_id,
        'status' => 'paid',
        'grant_date' => '2024-01-01',
        'end_date' => '2024-12-31',
    ]);

    $response->assertRedirect(route('admin.scholarship-awards.index'));
});

test('department_admin cannot update scholarship award from another department', function () {
    [$university, $department1] = createUniWithDept();
    $department2 = createDepartment($university);
    $admin = deptAdmin($department1, $university);
    $award = createScholarshipAward($department2, $university);

    $response = $this->actingAs($admin)->put(route('admin.scholarship-awards.update', $award), [
        'student_id' => $award->student_id,
        'scholarship_id' => $award->scholarship_id,
        'status' => 'paid',
        'grant_date' => '2024-01-01',
        'end_date' => '2024-12-31',
    ]);

    $response->assertStatus(403);
});

test('staff_admin cannot update scholarship award', function () {
    [$university, $department] = createUniWithDept();
    $admin = staffAdmin($department, $university);
    $award = createScholarshipAward($department, $university);

    $response = $this->actingAs($admin)->put(route('admin.scholarship-awards.update', $award), [
        'student_id' => $award->student_id,
        'scholarship_id' => $award->scholarship_id,
        'status' => 'paid',
        'grant_date' => '2024-01-01',
        'end_date' => '2024-12-31',
    ]);

    $response->assertStatus(403);
});

test('student cannot update scholarship award', function () {
    [$university, $department] = createUniWithDept();
    $studentUser = studentUser($department, $university);
    $award = createScholarshipAward($department, $university);

    $response = $this->actingAs($studentUser)->put(route('admin.scholarship-awards.update', $award), [
        'student_id' => $award->student_id,
        'scholarship_id' => $award->scholarship_id,
        'status' => 'paid',
        'grant_date' => '2024-01-01',
        'end_date' => '2024-12-31',
    ]);

    $response->assertStatus(403);
});