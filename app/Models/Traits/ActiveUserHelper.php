<?php
 
 namespace App\Models\Traits;
 
 use App\Models\Reply;
 use App\Models\Topic;
 use App\Models\User;
 use Illuminate\Support\Arr;
 use Illuminate\Support\Collection;
 use Illuminate\Support\Facades\Cache;
 use Illuminate\Support\Facades\DB;
 use PhpParser\ErrorHandler\Collecting;
 
 trait ActiveUserHelper
 {
     /**
      * 用于存放临时用户数据
      *
      * @var array
      */
     protected array $users = [];
 
     // 配置信息
     protected int $topic_weight = 4; // 话题权重
     protected int $reply_weight = 1; // 回复权重
     protected int $pass_days = 7; // 多少天内发表过内容
     protected int $user_number = 6; // 取出用户数量
 
     // 缓存相关配置
     protected string $cache_key = 'LuStormstoutBBS202501-active_users';
     protected int $cache_expire_in_seconds = 65 * 60; // 缓存过期时间 65 分钟
 
     /**
      * 获取活跃用户
      *
      * @return array|Collection
      */
     public function getActiveUsers(): array|Collection
     {
         // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，直接返回数据
         // 否则运行匿名函数中的代码来计算活跃用户数据，返回并且缓存起来
         return Cache::remember($this->cache_key, $this->cache_expire_in_seconds, function () {
             return $this->calculateActiveUsers();
         });
     }
 
     /**
      * 计算并缓存活跃用户
      *
      * @return void
      */
     public function calculateAndCacheActiveUsers(): void
     {
         $active_users = $this->calculateActiveUsers();
         $this->cacheActiveUsers($active_users);
     }
 
     /**
      * 计算活跃用户
      *
      * @return array|Collection
      */
     protected function calculateActiveUsers(): array|Collection
     {
         $this->calculateTopicScore();
         $this->calculateReplyScore();
 
         // 按照分数排序
         $users = Arr::sort($this->users, function ($user) {
             return $user['score'];
         });
 
         // 我们需要的是倒序，高分在前, array_reverse() 默认不保留原始的数组键，所以第二个参数设置为 true 保留 key 不变
         $users = array_reverse($users, true);
 
         // 取出前 $this->user_number 个用户
         $users = array_slice($users, 0, $this->user_number, true);
 
         // 新建一个空集合
         $active_users = collect();
 
         foreach ($users as $user_id => $user) {
             // 搜寻一下是否可以找到用户, 这里的 $this 是指调用了该 trait 的类, 我们接下来会在 User 模型中使用这个 trait 所以这里的 $this 指的是 User 模型
             $user = $this->find($user_id);
             if ($user) {
                 // 将此用户数据放入集合的末尾
                 $active_users->push($user);
             }
         }
 
         // 返回数据
         return $active_users;
     }
 
     /**
      * 缓存活跃用户
      *
      * @param $active_users
      * @return void
      */
     protected function cacheActiveUsers($active_users): void
     {
         // 将数据放入缓存中
         Cache::put($this->cache_key, $active_users, $this->cache_expire_in_seconds);
     }
 
     /**
      * 计算话题分数
      *
      * @return void
      */
     protected function calculateTopicScore(): void
     {
         // 从话题数据表中取出限定时间范围 $this->pass_days 内有发表过话题的用户
         // 并且同时取出用户此段时间内发表话题的数量
         $topic_users = Topic::query()->select(DB::raw('user_id, count(*) as topic_count'))
             ->where('created_at', '>=', now()->subDays($this->pass_days))
             ->groupBy('user_id')
             ->get();
 
         // 根据话题数量计算用户分数
         foreach ($topic_users as $user) {
             $this->users[$user->user_id]['score'] = $user->topic_count * $this->topic_weight;
         }
     }
 
     /**
      * 计算回复分数
      *
      * @return void
      */
     protected function calculateReplyScore(): void
     {
         // 从回复数据表中取出限定时间范围 $this->pass_days 内有发表过回复的用户
         // 并且同时取出用户此段时间内发表回复的数量
         $reply_users = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
             ->where('created_at', '>=', now()->subDays($this->pass_days))
             ->groupBy('user_id')
             ->get();
 
         // 根据回复数量计算用户分数
         foreach ($reply_users as $user) {
             $reply_score = $user->reply_count * $this->reply_weight;
             if (isset($this->users[$user->user_id])) {
                 $this->users[$user->user_id]['score'] += $reply_score;
             } else {
                 $this->users[$user->user_id]['score'] = $reply_score;
             }
         }
     }
 }