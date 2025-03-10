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
            $table->renameColumn('content', 'message'); // Rename the `content` column to `message`
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
            $table->renameColumn('message', 'content'); // Rename the `message` column to `content`
        });
    }
};
