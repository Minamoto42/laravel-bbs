<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends Factory<User>
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
        $createdAt = Carbon::now()->subDays(rand(0, 30))->addHours(rand(1, 24)); // 随机过去 30 天内的时间
        $updatedAt = (rand(0, 1) ? $createdAt->clone()->addHours(rand(1, 48)) : $createdAt); // 有一定概率相同
        $avatars = [
            config('app.url') . '/uploads/images/default-avatars/200.jpg',
            config('app.url') . '/uploads/images/default-avatars/300.jpg',
            config('app.url') . '/uploads/images/default-avatars/400.jpg',
            config('app.url') . '/uploads/images/default-avatars/500.jpg',
            config('app.url') . '/uploads/images/default-avatars/600.jpg',
        ];


        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'introduction' => $this->faker->sentence(),
            'avatar' => $this->faker->randomElement($avatars),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
