<?php

namespace Database\Factories;

use App\Models\Reply;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    protected $model = Reply::class;

    public function definition(): array
    {
        $createdAt = Carbon::now()->subDays(rand(0, 30))->addHours(rand(1, 24)); // 随机过去 30 天内的时间
        $updatedAt = (rand(0, 1) ? $createdAt->clone()->addHours(rand(1, 48)) : $createdAt); // 有一定概率相同

        return [
            'message' => $this->faker->sentence(),
            'topic_id' => rand(1, 100),
            'user_id' => rand(1, 10),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
