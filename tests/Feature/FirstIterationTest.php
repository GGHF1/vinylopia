<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class FirstIterationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration with valid data.
     */
    public function test_user_can_register(): void
    {
        DB::table('countries')->insert([
            'country_id' => 1,
            'name' => 'Latvia',
            'code' => 'LV',
            'continent' => 'Europe',
        ]);

        $response = $this->post('/signup', [
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'address' => '123 Main Street',
            'country_id' => 1,
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
        ]);
    }

    /**
     * Test user registration fails with invalid data.
     */
    public function test_user_registration_fails_with_invalid_data(): void
    {
        DB::table('countries')->insert([
            'country_id' => 1,
            'name' => 'Latvia',
            'code' => 'LV',
            'continent' => 'Europe',
        ]);

        $response = $this->post('/signup', [
            'fname' => 'John123',
            'lname' => 'Doe',
            'email' => 'invalid-email',
            'username' => 'johndoe',
            'password' => 'short',
            'password_confirmation' => 'short',
            'address' => 'Short',
            'country_id' => 999,
        ]);

        $response->assertSessionHasErrors([
            'fname',
            'email',
            'password',
            'address',
            'country_id',
        ]);
    }

    /**
     * Test user login with valid credentials.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        DB::table('countries')->insert([
            'country_id' => 1,
            'name' => 'Latvia',
            'code' => 'LV',
            'continent' => 'Europe',
        ]);

        User::create([
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'password' => bcrypt('Password123!'),
            'address' => '123 Main Street',
            'country_id' => 1,
        ]);

        $response = $this->post('/login', [
            'username' => 'johndoe',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect('/');
    }

    /**
     * Test user login fails with invalid credentials.
     */
    public function test_user_login_fails_with_invalid_credentials(): void
    {
        DB::table('countries')->insert([
            'country_id' => 1,
            'name' => 'Latvia',
            'code' => 'LV',
            'continent' => 'Europe',
        ]);

        User::create([
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'john.doe@example.com',
            'username' => 'johndoe',
            'password' => bcrypt('Password123!'),
            'address' => '123 Main Street',
            'country_id' => 1,
        ]);

        $response = $this->post('/login', [
            'username' => 'johndoe',
            'password' => 'WrongPassword!',
        ]);

        $response->assertSessionHasErrors(['login']);
    }
}