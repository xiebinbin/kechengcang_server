<?php

use App\Enums\Fields\OnlineStatus;
use App\Enums\Fields\RecommendStatus;
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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->comment('标题');
            $table->string('intro')->nullable()->comment('简介');
            $table->longText('detail')->nullable()->comment('详情');
            $table->longText('catalogue')->nullable()->comment('目录');
            $table->tinyInteger('recommend_status')->default(RecommendStatus::OFF)->comment('推荐状态')->index('recommend_status');
            $table->tinyInteger('online_status')->default(OnlineStatus::UP)->nullable()->comment('在线状态')->index('online_status');;
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
        Schema::dropIfExists('courses');
    }
};
