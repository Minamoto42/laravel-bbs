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
        $categories = [
            [
                'name' => 'Shared',
                'description' => 'Found something cool? Share it!',
            ],
            [
                'name' => 'Tutorial',
                'description' => 'Learn and explore new skills.',
            ],
            [
                'name' => 'Q&A',
                'description' => 'Stay friendly, help each other, and share knowledge!',
            ],
            [
                'name' => 'Announcement',
                'description' => 'Official site updates and important news.',
            ],
        ];

        \Illuminate\Support\Facades\DB::table('categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('categories')->truncate();
    }
};
