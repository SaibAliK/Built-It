<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->double('amount', 20, 2);
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending')->nullable();
            $table->enum('method', ['paypal', 'cash', 'other'])->default('paypal')->nullable();
            $table->longText('paypal_response')->nullable();
            $table->integer('updated_at')->nullable();
            $table->integer('created_at')->nullable();
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
        Schema::dropIfExists('withdraws');
    }
}
