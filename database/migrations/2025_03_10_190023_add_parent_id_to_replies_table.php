<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToRepliesTable extends Migration
{
    public function up()
    {
        Schema::table('replies', function (Blueprint $table) {
            // 增加 parent_id 字段，默认为 0，代表顶级回复
            $table->unsignedBigInteger('parent_id')->default(0)->index()->after('id');
        });
    }

    public function down()
    {
        Schema::table('replies', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
}
