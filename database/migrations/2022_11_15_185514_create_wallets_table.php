<?php

use App\Enums\Fields\DigitalCurrency;
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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_user_id')->comment('所属用户')->nullable()->default(0)->index('admin_user');
            $table->integer('digital_currency')->default(DigitalCurrency::NORMAL)->comment('货币')->index('digital_currency');
            $table->bigInteger('balance')->default(0)->comment('余额');
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
        Schema::dropIfExists('merchant_wwalletsallets');
    }
};
