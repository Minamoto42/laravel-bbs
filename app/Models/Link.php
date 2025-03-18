<?php
 
 namespace App\Models;
 
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Support\Facades\Cache;
 
 class Link extends Model
 {
     use HasFactory;
 
     protected $fillable = ['title', 'link'];
 
     /**
      * 缓存键, 就是在 Redis 中的 key
      *
      * @var string
      */
     public string $cacheKey = 'lustormstout_bbs_202501_links';
 
     /**
      * 缓存过期时间, 单位: 秒
      * 我们这里配置的是 1440 分钟, 也就是 24 小时
      *
      * @var int
      */
     protected int $cacheExpireInSeconds = 1440 * 60;
 
     /**
      * 获取所有的资源推荐链接, 并且缓存起来
      *
      * @return mixed
      */
     public function getAllCached(): mixed
     {
         // 尝试从缓存中取出 key 为 $this->cacheKey 的数据, 如果取出来了, 就直接返回
         // 如果取不出来, 就执行匿名函数, 并将匿名函数的返回值写入缓存, 然后返回
         return Cache::remember($this->cacheKey, $this->cacheExpireInSeconds, function () {
             return $this->all();
         });
     }
 }