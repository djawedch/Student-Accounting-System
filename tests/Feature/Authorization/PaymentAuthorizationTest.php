<?php

use App\Models\{University, Department, Fee, Invoice, Payment, Student};

// ─── Helpers ─────────────────────────────────────────────────────────────────

function createPayment(Department $department, University $university): Payment
{
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $fee = Fee::factory()->create(['department_id' => $department->id]);
    $invoice = Invoice::factory()->create(['student_id' => $student->id, 'fee_id' => $fee->id]);
    return Payment::factory()->create(['invoice_id' => $invoice->id]);
}

// ─── viewAny ────────────────────────────────────────────────────────────────

test('super_admin can view payments list', function () {
    $response = $this->actingAs(superAdmin())->get(route('admin.payments.index'));
    $response->assertStatus(200);
});

test('university_admin can view payments list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(uniAdmin($university))->get(route('admin.payments.index'));
    $response->assertStatus(200);
});

test('department_admin can view payments list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(deptAdmin($department, $university))->get(route('admin.payments.index'));
    $response->assertStatus(200);
});

test('staff_admin can view payments list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(staffAdmin($department, $university))->get(route('admin.payments.index'));
    $response->assertStatus(200);
});

test('student cannot view payments list', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(studentUser($department, $university))->get(route('admin.payments.index'));
    $response->assertStatus(403);
});

// ─── view ────────────────────────────────────────────────────────────────────

test('super_admin can view any payment', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $payment = createPayment($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.payments.show', $payment));

    // Assert
    $response->assertStatus(200);
});

test('university_admin can view payment in their own university', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = uniAdmin($university);
    $payment = createPayment($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.payments.show', $payment));

    // Assert
    $response->assertStatus(200);
});

test('university_admin cannot view payment from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $payment = createPayment($department2, $university2);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.payments.show', $payment));

    // Assert
    $response->assertStatus(403);
});

test('department_admin can view payment in their own department', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $admin = deptAdmin($department, $university);
    $payment = createPayment($department, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.payments.show', $payment));

    // Assert
    $response->assertStatus(200);
});

test('department_admin cannot view payment from another department', function () {
    // Arrange
    [$university, $department1] = createUniWithDept();
    $department2 = createDepartment($university);
    $admin = deptAdmin($department1, $university);
    $payment = createPayment($department2, $university);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.payments.show', $payment));

    // Assert
    $response->assertStatus(403);
});

test('student can view their own payment', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $fee = Fee::factory()->create(['department_id' => $department->id]);
    $invoice = Invoice::factory()->create(['student_id' => $student->id, 'fee_id' => $fee->id]);
    $payment = Payment::factory()->create(['invoice_id' => $invoice->id]);

    // Act
    $response = $this->actingAs($studentUser)->get(route('student.payments.show', $payment));

    // Assert
    $response->assertStatus(200);
});

test('student cannot view another student payment', function () {
    // Arrange
    [$university, $department] = createUniWithDept();

    // Student 1 — the one trying to view
    $studentUser1 = studentUser($department, $university);
    Student::factory()->create(['user_id' => $studentUser1->id]);

    // Student 2 — owns the payment
    $payment = createPayment($department, $university);

    // Act
    $response = $this->actingAs($studentUser1)->get(route('student.payments.show', $payment));

    // Assert
    $response->assertStatus(403);
});

// ─── create ──────────────────────────────────────────────────────────────────

test('super_admin can access create payment form', function () {
    $response = $this->actingAs(superAdmin())->get(route('admin.payments.create'));
    $response->assertStatus(200);
});

test('university_admin can access create payment form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(uniAdmin($university))->get(route('admin.payments.create'));
    $response->assertStatus(200);
});

test('department_admin can access create payment form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(deptAdmin($department, $university))->get(route('admin.payments.create'));
    $response->assertStatus(200);
});

test('staff_admin can access create payment form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(staffAdmin($department, $university))->get(route('admin.payments.create'));
    $response->assertStatus(200);
});

test('student cannot access create payment form', function () {
    [$university, $department] = createUniWithDept();
    $response = $this->actingAs(studentUser($department, $university))->get(route('admin.payments.create'));
    $response->assertStatus(403);
});

test('super_admin can create a payment', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $studentUser = studentUser($department, $university);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $fee = Fee::factory()->create(['department_id' => $department->id]);
    $invoice = Invoice::factory()->create(['student_id' => $student->id, 'fee_id' => $fee->id, 'status' => 'unpaid']);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.payments.store'), [
        'invoice_id'     => $invoice->id,
        'amount'         => 1000,
        'payment_method' => 'cash',
        'payment_date'   => '2024-01-15',
    ]);

    // Assert
    $response->assertRedirect();
    $this->assertDatabaseHas('payments', ['invoice_id' => $invoice->id, 'amount' => 1000]);
});

test('university_admin cannot create payment for invoice from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $studentUser = studentUser($department2, $university2);
    $student = Student::factory()->create(['user_id' => $studentUser->id]);
    $fee = Fee::factory()->create(['department_id' => $department2->id]);
    $invoice = Invoice::factory()->create(['student_id' => $student->id, 'fee_id' => $fee->id]);

    // Act
    $response = $this->actingAs($admin)->post(route('admin.payments.store'), [
        'invoice_id'     => $invoice->id,
        'amount'         => 1000,
        'payment_method' => 'cash',
        'payment_date'   => '2024-01-15',
    ]);

    // Assert
    $response->assertStatus(403);
});

// ─── update ──────────────────────────────────────────────────────────────────

test('super_admin can update any payment', function () {
    // Arrange
    $admin = superAdmin();
    [$university, $department] = createUniWithDept();
    $payment = createPayment($department, $university);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.payments.update', $payment), [
        'amount'         => 2000,
        'payment_method' => 'bank_transfer',
        'payment_date'   => '2024-01-20',
    ]);

    // Assert
    $response->assertRedirect(route('admin.payments.show', $payment));
    $this->assertDatabaseHas('payments', ['id' => $payment->id, 'amount' => 2000]);
});

test('university_admin cannot update payment from another university', function () {
    // Arrange
    [$university1, $department1] = createUniWithDept();
    [$university2, $department2] = createUniWithDept();
    $admin = uniAdmin($university1);
    $payment = createPayment($department2, $university2);

    // Act
    $response = $this->actingAs($admin)->put(route('admin.payments.update', $payment), [
        'amount'         => 2000,
        'payment_method' => 'bank_transfer',
        'payment_date'   => '2024-01-20',
    ]);

    // Assert
    $response->assertStatus(403);
});

test('student cannot update a payment', function () {
    // Arrange
    [$university, $department] = createUniWithDept();
    $student = studentUser($department, $university);
    $payment = createPayment($department, $university);

    // Act
    $response = $this->actingAs($student)->put(route('admin.payments.update', $payment), [
        'amount'         => 2000,
        'payment_method' => 'cash',
        'payment_date'   => '2024-01-20',
    ]);

    // Assert
    $response->assertStatus(403);
});
