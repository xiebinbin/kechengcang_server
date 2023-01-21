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
        Schema::create('category_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('channel_id')->comment('频道')->index('channel');
            $table->unsignedBigInteger('subject_id')->comment('栏目')->index('subject');
            $table->unsignedBigInteger('category_id')->comment('分类')->index('category');
            $table->unsignedBigInteger('course_id')->comment('课程')->index('course');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('category_courses');
    }
};
