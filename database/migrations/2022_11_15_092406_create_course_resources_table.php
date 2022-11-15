<?php

use App\Enums\Fields\OnlineStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('course_resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->comment('课程')->index('course')->index('course');
            $table->tinyInteger('type')->nullable()->default(1)->comment('类型')->index('type');
            $table->text('content')->comment('内容');
            $table->tinyInteger('online_status')->nullable()->default(OnlineStatus::UP)->comment('在线状态')->index('online_status');
            $table->integer('sort')->nullable()->comment('排序')->index('sort');
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
        Schema::dropIfExists('course_resources');
    }
};
