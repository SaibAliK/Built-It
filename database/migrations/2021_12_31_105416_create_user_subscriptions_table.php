<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->enum('type',['package', 'commission']);
            $table->longText('package');
            $table->boolean('is_expired')->default(0);
            $table->string('payment_status',255)->nullable();
            $table->string('payer_id',255)->nullable();
            $table->string('first_name',255)->nullable();
            $table->string('last_name',255)->nullable();
            $table->string('payment_id',255)->nullable();
            $table->string('payer_email',255)->nullable();
            $table->string('payer_status',255)->nullable();
            $table->enum('payment_method',['paypal', 'free', 'admin'])->default('free');
            $table->longText('paypal_response')->nullable();
            $table->string('aed_price',255)->nullable();
            $table->string('commission',255)->nullable();
            $table->string('currency',255)->nullable();
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
            $table->integer('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_subscriptions');
    }
}
