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
        Schema::table('questions', function (Blueprint $table) {
            $table->string('media_url')->nullable()->after('question_text');
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->string('media_url')->nullable()->after('answer_text');
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('media_url');
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->dropColumn('media_url');
        });
    }
};
