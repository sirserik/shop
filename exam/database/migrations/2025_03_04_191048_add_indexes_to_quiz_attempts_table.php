<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->index('quiz_id', 'quiz_attempts_quiz_id_index');
        });
    }

    public function down()
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropIndex('quiz_attempts_quiz_id_index');
        });
    }
};
