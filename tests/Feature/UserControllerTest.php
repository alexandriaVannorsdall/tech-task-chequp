<?php

namespace Tests\Feature\Domains\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /** @test */
    public function it_can_list_users()
    {
        // Arrange
        User::factory()->count(2)->create();

        // Act
        $response = $this->getJson(route('users.index'));

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure(['users' => [['id', 'first_name', 'last_name', 'email', 'phone', 'country', 'gender']]]);
    }

    /** @test */
    public function it_can_create_a_user()
    {
        // Arrange
        $userData = [
            'first_name' => $this->faker->firstName,
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->unique()->safeEmail,
            'phone'      => $this->faker->numerify('##########'),
            'country'    => $this->faker->country,
            'gender'     => 'male',
            'password'   => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Act
        $response = $this->postJson(route('users.store'), $userData);

        // Assert
        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'User created successfully.',
                 ]);

        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    }

    /** @test */
    public function it_can_update_a_user()
    {
        // Arrange
        $user = User::factory()->create();
        $updateData = [
            'first_name' => 'UpdatedFirstName',
            'last_name'  => 'UpdatedLastName',
            'email'      => $this->faker->unique()->safeEmail, // Ensure email uniqueness
            'gender'     => 'female'
        ];

        // Act
        $response = $this->putJson(route('users.update', $user), $updateData);

        // Assert
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'User updated successfully.',
                 ]);

        $this->assertDatabaseHas('users', [
            'id'         => $user->id,
            'first_name' => 'UpdatedFirstName',
            'last_name'  => 'UpdatedLastName',
        ]);
    }

    /** @test */
    public function it_can_show_a_user()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->getJson(route('users.show', $user));

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure(['user' => ['id', 'first_name', 'last_name', 'email', 'phone', 'country', 'gender']]);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->deleteJson(route('users.destroy', $user));

        // Assert
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'User deleted successfully.',
                 ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}