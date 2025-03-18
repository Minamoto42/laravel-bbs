<?php
 
 use App\Models\Link;
 use Illuminate\Support\Facades\Auth;
 
 return [
     'title' => 'Links',
     'single' => 'Link',
 
     'model' => Link::class,
 
     // 访问权限判断
     'permission' => function () {
         // 只允许站长管理资源推荐链接
         return Auth::user()->hasRole('Founder');
     },
 
     // 列表中需要展示的字段
     'columns' => [
         'id' => [
             'title' => 'ID',
         ],
         'title' => [
             'title' => '名称',
             'sortable' => false,
         ],
         'link' => [
             'title' => '链接',
             'sortable' => false,
         ],
         'operation' => [
             'title' => '管理',
             'sortable' => false,
         ],
     ],
 
     // 允许编辑的字段
     'edit_fields' => [
         'title' => [
             'title' => '名称',
         ],
         'link' => [
             'title' => '链接',
         ],
     ],
 
     // 允许搜索的字段
     'filters' => [
         'id' => [
             'title' => '标签 ID',
         ],
         'title' => [
             'title' => '名称',
         ],
     ],
 ];