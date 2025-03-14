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
        Schema::table('replies', function (Blueprint $table) {
            $table->integer('parent_id')->unsigned()->nullable(false)->default(0)->comment('父级 ID')->after('topic_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('replies', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
};