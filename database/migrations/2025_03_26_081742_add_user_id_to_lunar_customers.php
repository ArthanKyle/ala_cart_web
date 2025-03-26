<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lunar_customers', function (Blueprint $table) {
            // Add the user_id column next to id
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->after('id');
        });
    }

    public function down()
    {
        Schema::table('lunar_customers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
