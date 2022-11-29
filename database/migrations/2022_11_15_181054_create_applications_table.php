<?php

use App\Enums\Fields\ApplicationStatus;
use App\Enums\Fields\CheckStatus;
use App\Enums\Fields\SystemStatus;
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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_user_id')->comment('所属用户')->nullable()->default(0)->index('admin_user');
            $table->string('name')->nullable()->comment('名称');
            $table->text('remark')->comment('备注');
            $table->string('app_id',128)->nullable()->comment('应用ID')->index('app_id');
            $table->string('secure_key',128)->nullable()->comment('安全key');
            $table->tinyInteger('check_status')->nullable()->default(CheckStatus::WAIT)->comment('审核状态')->index('check_status');
            $table->tinyInteger('status')->nullable()->default(ApplicationStatus::ENABLE)->comment('状态')->index('status');
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
        Schema::dropIfExists('applications');
    }
};
