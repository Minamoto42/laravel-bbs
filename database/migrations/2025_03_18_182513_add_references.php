<?php
 
 use Illuminate\Database\Migrations\Migration;
 use Illuminate\Database\Schema\Blueprint;
 use Illuminate\Support\Facades\Schema;
 
 return new class extends Migration {
     /**
      * Run the migrations.
      *
      * @return void
      */
     public function up(): void
     {
         // 创建外键约束
 
         Schema::table('topics', function (Blueprint $table) {
            if (!Schema::hasColumn('topics', 'user_id')) {
 
             // 当 user_id 对应的 users 表中的用户被删除的时候, 就会删除对应该用户的所有话题
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
         });
 
         Schema::table('replies', function (Blueprint $table) {
            if (!Schema::hasColumn('replies', 'user_id')) {
 
             // 当 user_id 对应的 users 表中的用户被删除的时候, 就会删除对应该用户的所有回复
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
 
             // 当 topic_id 对应的 topics 表中的话题被删除的时候, 就会删除对应该话题的所有回复
             $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
            }
         });
     }
 
     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down(): void
     {
         // 移除外键约束
 
         Schema::table('topics', function (Blueprint $table) {
             $table->dropForeign(['user_id']);
         });
 
         Schema::table('replies', function (Blueprint $table) {
             $table->dropForeign(['user_id']);
             $table->dropForeign(['topic_id']);
         });
     }
 };