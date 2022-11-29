<?php

use App\Enums\Fields\CallbackStatus;
use App\Enums\Fields\DigitalCurrency;
use App\Enums\Fields\LegalCurrency;
use App\Enums\Fields\OrderStatus;
use App\Enums\Fields\PayStatus;
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
        Schema::create('pay_orders', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->comment('标题');
            $table->string('txhash')->nullable()->comment('转账hash');
            $table->unsignedBigInteger('admin_user_id')->comment('所属账户')->nullable()->default(0)->index('admin_user_id');
            $table->unsignedBigInteger('application_id')->comment('所属应用')->nullable()->default(0)->index('application');
            $table->string('remark')->nullable()->comment('备注');
            $table->bigInteger('fee')->nullable()->default(0)->comment('金额');
            $table->string('currency')->nullable()->default(LegalCurrency::USD)->comment('货币');
            $table->bigInteger('pay_fee')->nullable()->default(0)->comment('支付金额');
            $table->string('pay_currency')->nullable()->default(DigitalCurrency::NORMAL)->comment('支付货币');
            $table->string('pay_account')->nullable()->default('')->comment('支付账号');
            $table->string('receipt_account')->nullable()->default('')->comment('收款账号');
            $table->string('callback_url')->nullable()->default('')->comment('回调地址');
            $table->string('redirect_url')->nullable()->default('')->comment('跳转地址');
            $table->tinyInteger('pay_status')->nullable()->default(PayStatus::NORMAL)->comment('支付状态')->index('pay_status');
            $table->tinyInteger('status')->nullable()->default(OrderStatus::NORMAL)->comment('订单状态')->index('status');
            $table->tinyInteger('callback_status')->nullable()->default(CallbackStatus::NORMAL)->comment('回调状态')->index('callback_status');
            $table->timestamp('callback_at')->nullable()->comment('回调时间');
            $table->tinyInteger('callback_times')->nullable()->default(0)->comment('回调次数');
            $table->timestamp('pay_finish_at')->nullable()->comment('支付完成时间');
            $table->timestamp('pay_refresh_at')->nullable()->comment('支付刷新时间');
            $table->timestamp('pay_submit_at')->nullable()->comment('支付提交时间');
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
        Schema::dropIfExists('pay_orders');
    }
};
