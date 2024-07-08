<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->integer('expiry_date')->nullable();
            $table->string('user_name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->longText('image')->nullable();
            $table->longText('id_card_images')->nullable();
            $table->tinyInteger('is_verified')->default(0)->nullable();
            $table->tinyInteger('is_active')->default(1)->nullable();
            $table->tinyInteger('is_id_card_verified')->default(0)->nullable();
            $table->string('verification_code')->nullable();
            $table->string('address')->nullable();
            $table->double('latitude', 20, 10)->nullable();
            $table->double('longitude', 20, 10)->nullable();
            $table->enum('user_type', ['user','supplier','rider','employee','company'])->default('user');
            $table->longText('supplier_name')->nullable();
            $table->longText('about')->nullable();
            $table->float('rating')->default(0)->nullable();
            $table->string('client_id')->nullable();
            $table->string('secret_id')->nullable();
            $table->double('amount_on_hold', 20, 2)->nullable();
            $table->double('available_balance', 20, 2)->nullable();
            $table->double('total_earning', 20, 2)->nullable();
            $table->tinyInteger('settings')->default(1)->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();
            $table->unsignedInteger('created_at')->nullable();
            $table->unsignedInteger('updated_at')->nullable();
            $table->unsignedInteger('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
