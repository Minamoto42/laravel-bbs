<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->integer('topic_id')->unsigned()->default(0)->index()->comment('对应的话题 ID');
            $table->bigInteger('user_id')->unsigned()->default(0)->index()->comment('回复的用户 ID');
            $table->text('content')->comment('回复内容');
            $table->timestamps();
            $table->comment('话题回复表');
        });
    }

    public function down(): void
    {
        Schema::drop('replies');
    }
};
