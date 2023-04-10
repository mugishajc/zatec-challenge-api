<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => fake()->name(),
      'email' => fake()->unique()->safeEmail(),
      'picture' => 'https://lh3.googleusercontent.com/a/AGNmyxaOWcDXmEBKQ4GuT7mged-we_XtihgS7q3yntKULw=s96-c',
      'verified_email' => true,
    ];
  }
}
