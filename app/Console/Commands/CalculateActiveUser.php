<?php
 
 namespace App\Console\Commands;
 
 use App\Models\User;
 use Illuminate\Console\Command;
 
 class CalculateActiveUser extends Command
 {
     /**
      * The name and signature of the console command.
      *
      * @var string
      */
     protected $signature = 'bbs:calculate-active-user';
 
     /**
      * The console command description.
      *
      * @var string
      */
     protected $description = '生成活跃用户';
 
     /**
      * Execute the console command.
      *
      * @param User $user
      */
     public function handle(User $user): void
     {
         $this->info('开始计算活跃用户...');
 
         // 调用 User 模型的 calculateAndCacheActiveUsers 方法生成活跃用户数据
         $user->calculateAndCacheActiveUsers();
 
         $this->info('计算活跃用户成功！');
     }
 }