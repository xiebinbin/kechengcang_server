<?php

use App\Enums\Fields\OnlineStatus;
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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('channel_id')->default(0)->comment('所属频道')->index('channel');
            $table->string('icon_url')->nullable()->comment('图标');
            $table->string('title')->nullable()->comment('名称');
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
        Schema::dropIfExists('subjects');
    }
};
