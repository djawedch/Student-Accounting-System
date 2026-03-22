<?php

use App\Models\{User, University, Department, Fee, Invoice, Student};
use Illuminate\Support\Facades\Hash;

// ─── viewAny ────────────────────────────────────────────────────────────────

test('super_admin can view invoices list', function () {
    $response = $this->actingAs(superAdmin())->get(route('admin.invoices.index'));
    
    $response->assertStatus(200);
});

test('university_admin can view invoices list', function () {
    [$university, $department] = createUniWithDept();

    $response = $this->actingAs(uniAdmin($university))->get(route('admin.invoices.index'));

    $response->assertStatus(200);
});

test('department_admin can view invoices list', function () {
    [$university, $department] = createUniWithDept();

    $response = $this->actingAs(deptAdmin($department, $university))->get(route('admin.invoices.index'));

    $response->assertStatus(200);
});

test('staff_admin can view invoices list', function () {
    [$university, $department] = createUniWithDept();

    $response = $this->actingAs(staffAdmin($department, $university))->get(route('admin.invoices.index'));

    $response->assertStatus(200);
});

test('student cannot view invoices list', function () {
    [$university, $department] = createUniWithDept();

    $response = $this->actingAs(studentUser($department, $university))->get(route('admin.invoices.index'));

    $response->assertStatus(403);
});

// ─── Helpers ─────────────────────────────────────────────────────────────────

function createInvoice(Department $department, University $university): Invoice
{
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $fee = Fee::factory()->create(['department_id' => $department->id]);
    return Invoice::factory()->create(['student_id' => $student->id, 'fee_id' => $fee->id]);
}

// ─── view ────────────────────────────────────────────────────────────────────

test('super_admin can view any invoice', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $invoice = createInvoice($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.invoices.show', $invoice));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can view invoice in their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $invoice = createInvoice($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.invoices.show', $invoice));

    // Assert
    $response->assertStatus(200);
});

test('university_admin cannot view invoice from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $invoice = createInvoice($department2, $university2);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.invoices.show', $invoice));

    // Assert
    $response->assertStatus(403);
});

test('department_admin can view invoice in their own department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $invoice = createInvoice($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.invoices.show', $invoice));

    // Assert
    $response->assertStatus(200);
});

test('department_admin cannot view invoice from another department', function () {
    // Arrange
    [$university, $department1] = createUniWithDept();
    $department2 = Department::factory()->create(['university_id' => $university->id]);
    $admin = deptAdmin($department1, $university);
    $invoice = createInvoice($department2, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.invoices.show', $invoice));

    // Assert
    $response->assertStatus(403);
});

test('student can view their own invoice', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $fee = Fee::factory()->create(['department_id' => $department->id]);
    $invoice = Invoice::factory()->create(['student_id' => $student->id, 'fee_id' => $fee->id]);

    // Act
    $response = $this->actingAs($studentUser)->get(route('student.invoices.show', $invoice));

    // Assert
    $response->assertStatus(200);
});

test('student cannot view another student invoice', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $studentUser1 = studentUser($department, $university);
    $invoice = createInvoice($department, $university);

    // Act
    $response = $this->actingAs($studentUser1)->get(route('student.invoices.show', $invoice));

    // Assert
    $response->assertStatus(403);
});

// ─── create ──────────────────────────────────────────────────────────────────

test('super_admin can access create invoice form', function () {
    $response = $this->actingAs(superAdmin())->get(route('admin.invoices.create'));
    $response->assertStatus(200);
});

test('university_admin can access create invoice form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(uniAdmin($university))->get(route('admin.invoices.create'));
    $response->assertStatus(200);
});

test('department_admin can access create invoice form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(deptAdmin($department, $university))->get(route('admin.invoices.create'));
    $response->assertStatus(200);
});

test('staff_admin can access create invoice form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(staffAdmin($department, $university))->get(route('admin.invoices.create'));
    $response->assertStatus(200);
});

test('student cannot access create invoice form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(studentUser($department, $university))->get(route('admin.invoices.create'));
    $response->assertStatus(403);
});

test('super_admin can create invoices', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $fee = Fee::factory()->create(['department_id' => $department->id]);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.invoices.store'), [
        'student_ids'  => [$student->id],
        'fee_ids'      => [$fee->id],
        'issued_date'  => '2024-01-01',
        'due_date'     => '2024-02-01',
    ]);

    // Assert
    $response->assertRedirect(route('admin.invoices.index'));
    $this->assertDatabaseHas('invoices', ['student_id' => $student->id, 'fee_id' => $fee->id]);
});

test('university_admin cannot create invoice for student from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $studentUser = studentUser($department2, $university2);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $fee = Fee::factory()->create(['department_id' => $department1->id]);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.invoices.store'), [
        'student_ids'  => [$student->id],
        'fee_ids'      => [$fee->id],
        'issued_date'  => '2024-01-01',
        'due_date'     => '2024-02-01',
    ]);

    // Assert
    $response->assertStatus(403);
});

// ─── update ──────────────────────────────────────────────────────────────────

test('super_admin can update any invoice', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $invoice = createInvoice($department, $university);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.invoices.update', $invoice), [
        'status'       => 'paid',
        'issued_date'  => '2024-01-01',
        'due_date'     => '2024-02-01',
    ]);

    // Assert
    $response->assertRedirect(route('admin.invoices.show', $invoice));
    $this->assertDatabaseHas('invoices', ['id' => $invoice->id, 'status' => 'paid']);
});

test('university_admin cannot update invoice from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $invoice = createInvoice($department2, $university2);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.invoices.update', $invoice), [
        'status'       => 'paid',
        'issued_date'  => '2024-01-01',
        'due_date'     => '2024-02-01',
    ]);

    // Assert
    $response->assertStatus(403);
});

test('student cannot update an invoice', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);
    $invoice = createInvoice($department, $university);

    // Act
    $response = $this->actingAs($student)->put(route('admin.invoices.update', $invoice), [
        'status'       => 'paid',
        'issued_date'  => '2024-01-01',
        'due_date'     => '2024-02-01',
    ]);

    // Assert
    $response->assertStatus(403);
});