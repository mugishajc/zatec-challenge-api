<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  /**
   * Test the index method.
   *
   * @return void
   */
  public function testIndex()
  {
    $users = User::factory()->count(3)->create();
    $response = $this->getJson('/api/users');
    $response->assertOk();
    $response->assertJson(['data' => $users->toArray()]);
  }
  /**
   * Test the store method.
   *
   * @return void
   */
  /** @test */
  public function it_can_create_a_new_user()
  {
    $user = [
      'name' => 'John Doe',
      'email' => 'johndoe@example.com',
      'picture' => 'https://example.com/image.jpg',
      'verified_email' => true,
      'favourite_albums' => ['Album 1', 'Album 2'],
      'favourite_artists' => ['Artist 1', 'Artist 2'],
    ];

    $response = $this->postJson('/api/users', $user);

    $response->assertStatus(201);
    $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);
  }

  /** @test */
  public function it_can_return_existing_user_with_same_email()
  {
    $existingUser = User::factory()->create([
      'email' => 'johndoe@example.com'
    ]);

    $user = [
      'name' => 'John Doe',
      'email' => 'johndoe@example.com',
      'picture' => 'https://example.com/image.jpg',
      'verified_email' => true,
      'favourite_albums' => ['Album 1', 'Album 2'],
      'favourite_artists' => ['Artist 1', 'Artist 2'],
    ];

    $response = $this->postJson('/api/users', $user);

    $response->assertStatus(200);
  }
  public function testUpdateAddsToFavourites()
  {
    $user = User::factory()->create([
      'email' => "test@gmail.com",
      'favourite_albums' => 'album1,,,album2,,,album3',
      'favourite_artists' => 'artist1,,,artist2,,,artist3',
    ]);

    $type = 'album';
    $email = 'test@gmail.com';
    $action = 'add';
    $url = 'album4';

    $response = $this->putJson("/api/users/favourite", [
      'email' => $email,
      'type' => $type,
      'action' => $action,
      'url' => $url,
    ]);

    $response->assertStatus(200);
  }

  public function testUpdateRemovesFromFavourites()
  {
    $user = User::factory()->create([
      'email' => 'test@gmail.com',
      'favourite_albums' => 'album1,,,album2,,,album3',
      'favourite_artists' => 'artist1,,,artist2,,,artist3',
    ]);

    $type = 'album';
    $email = 'test@gmail.com';
    $action = 'remove';
    $url = 'album2';

    $response = $this->putJson("/api/users/favourite", [
      'type' => $type,
      'email' => $email,
      'action' => $action,
      'url' => $url,
    ]);

    $response->assertStatus(200);
  }

  public function testUpdateUserNotFound()
  {
    $response = $this->putJson("/api/users/favourite", [
      'type' => 'album',
      'action' => 'add',
      'url' => 'album1',
    ]);

    $response->assertStatus(404);
  }

  public function testUpdateFavouriteAlreadyExists()
  {
    $user = User::factory()->create([
      'email' => 'test@gmail.com',
      'favourite_albums' => 'album1,,,album2,,,album3',
      'favourite_artists' => 'artist1,,,artist2,,,artist3',
    ]);

    $type = 'album';
    $email = 'test@gmail.com';
    $action = 'add';
    $url = 'album2';

    $response = $this->putJson("/api/users/favourite", [
      'type' => $type,
      'email' => $email,
      'action' => $action,
      'url' => $url,
    ]);

    $response->assertStatus(400);
    $response->assertJsonFragment([
      'message' => "{$type} already in favourites!",
    ]);
  }
}
