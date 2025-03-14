<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reply;

class RepliesTableSeeder extends Seeder
{
    // 跳过模型事件, 避免填充数据时触发事件执行时间太久
    // 我们在 ReplyObserver 中监听了 created 事件, 用来更新话题回复数和通知话题作者, 我们在填充数据时不需要这个事件
    // 我们还在 ReplyObserver 中监听了 creating 事件, 用来过滤用户输入的内容, 我们在填充数据时也不需要这个事件
    use WithoutModelEvents;

    public function run(): void
    {
        // 生成 1000 条顶级回复（parent_id = 0），并且这些回复的 topic_id 应该与实际话题关联
        $topReplies = Reply::factory()->count(1000)->create([
            'parent_id' => 0,
        ]);

        // 为每条顶级回复随机生成 0 至 3 条楼中楼回复
        foreach ($topReplies as $reply) {
            $childCount = rand(0, 3);
            if ($childCount > 0) {
                Reply::factory()->count($childCount)->create([
                    'parent_id' => $reply->id,
                    // 楼中楼回复的 topic_id 按要求设置为 0
                    'topic_id'  => 0,
                ]);
            }
        }
        \Log::info("为回复 {$reply->id} 生成 {$childCount} 个子回复");


        // 额外生成一些 parent_id = 1 的楼中楼回复（回复 id 为 1 的顶级回复）
        $parentReply = Reply::find(1);
        if ($parentReply) {
            // 例如再生成 5 条楼中楼回复，所有的 parent_id 都为 1
            Reply::factory()->count(5)->create([
                'parent_id' => $parentReply->id,
                'topic_id'  => 0,
            ]);
        }
    }
}
