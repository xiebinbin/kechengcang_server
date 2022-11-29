<?php

use App\Enums\Fields\AdminUserStatus;
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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('名称')->index('name');
            $table->string('role', 128)->nullable()->comment('角色')->index('role');
            $table->string('password', 128)->nullable()->comment('密码');
            $table->text('remark')->comment('备注');
            $table->tinyInteger('status')->nullable()->default(AdminUserStatus::ENABLE)->comment('状态')->index('status');
            $table->timestamp('last_active_at')->nullable()->comment('最后活动时间');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users');
    }
};
