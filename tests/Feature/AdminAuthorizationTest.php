<?php

use App\Models\Role;
use App\Models\User;

// G-10: Feature test: Admin route protection — customer cannot access admin routes

function adminUser(): User
{
    $role = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
    return User::factory()->create(['role_id' => $role->id, 'status' => 'active']);
}

function customerUser(): User
{
    $role = Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
    return User::factory()->create(['role_id' => $role->id, 'status' => 'active']);
}

test('unauthenticated user is redirected to login from admin dashboard', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertRedirect(route('login'));
});

test('customer user gets 403 on admin dashboard', function () {
    $customer = customerUser();

    $response = $this->actingAs($customer)->get(route('admin.dashboard'));

    $response->assertForbidden();
});

test('customer user gets 403 on admin products list', function () {
    $customer = customerUser();

    $response = $this->actingAs($customer)->get(route('admin.products.index'));

    $response->assertForbidden();
});

test('customer user gets 403 on admin orders list', function () {
    $customer = customerUser();

    $response = $this->actingAs($customer)->get(route('admin.orders.index'));

    $response->assertForbidden();
});

test('admin user can access admin dashboard', function () {
    $admin = adminUser();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
});
