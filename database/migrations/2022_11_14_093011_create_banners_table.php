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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('channel_id')->nullable()->comment('所属频道')->index('channel');
            $table->string('image_url')->nullable()->comment('图片地址');
            $table->tinyInteger('link_type')->nullable()->comment('链接类型');
            $table->string('link_address')->nullable()->comment('链接地址');
            $table->tinyInteger('online_status')->default(OnlineStatus::UP)->nullable()->comment('在线状态')->index('online_status');
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
        Schema::dropIfExists('banners');
    }
};
