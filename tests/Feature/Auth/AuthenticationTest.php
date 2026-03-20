<?php

use App\Models\{User, University, Department};
use Illuminate\Support\Facades\Hash;

test('login page renders successfully', function () {
    // Arrange — no setup needed

    // Act
    $response = $this->get('/login');

    // Assert
    $response->assertStatus(200);
});

test('super admin can login and is redirected to admin dashboard', function () {
    // Arrange
    $user = User::factory()->superAdmin()->create([
        'email'    => 'superadmin@test.com',
        'password' => Hash::make('password'),
    ]);

    // Act
    $response = $this->post('/login', [
        'email'    => 'superadmin@test.com',
        'password' => 'password',
    ]);

    // Assert
    $this->assertAuthenticated();
    $response->assertRedirect(route('admin.dashboard'));
});

test('student can login and is redirected to student dashboard', function () {
    // Arrange
    $university = University::factory()->create();
    $department = Department::factory()->create(['university_id' => $university->id]);
    $user = User::factory()->student($department->id, $university->id)->create([
        'email'    => 'student@test.com',
        'password' => Hash::make('password'),
    ]);

    // Act
    $response = $this->post('/login', [
        'email'    => 'student@test.com',
        'password' => 'password',
    ]);

    // Assert
    $this->assertAuthenticated();
    $response->assertRedirect(route('student.dashboard'));
});

test('user cannot login with wrong password', function () {
    // Arrange
    $user = User::factory()->superAdmin()->create([
        'email'    => 'superadmin@test.com',
        'password' => Hash::make('password'),
    ]);

    // Act
    $this->post('/login', [
        'email'    => 'superadmin@test.com',
        'password' => 'wrong-password',
    ]);

    // Assert
    $this->assertGuest();
});

test('inactive user cannot login', function () {
    // Arrange
    $user = User::factory()->superAdmin()->create([
        'email'     => 'inactive@test.com',
        'password'  => Hash::make('password'),
        'is_active' => false,
    ]);

    // Act
    $response = $this->post('/login', [
        'email'    => 'inactive@test.com',
        'password' => 'password',
    ]);

    // Assert
    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});

test('authenticated user can logout', function () {
    // Arrange
    $user = User::factory()->superAdmin()->create();

    // Act
    $response = $this->actingAs($user)->post('/logout');

    // Assert
    $this->assertGuest();
    $response->assertRedirect('/');
});

test('guest cannot access admin dashboard', function () {
    // Arrange — no setup needed

    // Act
    $response = $this->get(route('admin.dashboard'));

    // Assert
    $response->assertRedirect(route('login'));
});

test('student cannot access admin dashboard', function () {
    // Arrange
    $university = University::factory()->create();
    $department = Department::factory()->create(['university_id' => $university->id]);
    $user = User::factory()->student($department->id, $university->id)->create();

    // Act
    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    // Assert
    $response->assertStatus(403);
});

test('admin cannot access student dashboard', function () {
    // Arrange
    $user = User::factory()->superAdmin()->create();

    // Act
    $response = $this->actingAs($user)->get(route('student.dashboard'));

    // Assert
    $response->assertStatus(403);
});
