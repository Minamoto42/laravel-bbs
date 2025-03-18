<?php
 
 namespace App\Observers;
 
 use App\Models\Link;
 use Illuminate\Support\Facades\Cache;
 
 class LinkObserver
 {
     /**
      * Handle the Link "saved" event.
      * 这个方法会在 Link 模型被创建或者更新的时候触发
      * 当 Link 模型被创建或者更新的时候, 我们会清除缓存, 这样下次请求的时候就会重新从数据库中获取数据并且缓存起来
      * 这个类需要在 AppServiceProvider 中注册, 否则不会生效!!
      *
      * @param Link $link
      * @return void
      */
     public function saved(Link $link): void
     {
         Cache::forget($link->cacheKey);
     }
 }