<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->longText('privacy_policy')->nullable();
            $table->longText('terms_and_conditions')->nullable();
            $table->longText('contact_us')->nullable();
            $table->longText('mission_and_vision')->nullable();
            $table->longText('about_us')->nullable();

            $table->longText('address')->nullable();
            $table->longText('latitude')->nullable();
            $table->longText('longitude')->nullable();
            $table->enum('revenue_stream', ['commission','subscription'])->default('subscription');

            $table->longText('email_header')->nullable();

            $table->text('company_name')->nullable();
            $table->text('contact_number')->nullable();
            $table->text('email')->nullable();

            $table->longText('facebook_url')->nullable();
            $table->longText('instagram_url')->nullable();
            $table->longText('twitter_url')->nullable();

            $table->longText('android_app')->nullable();
            $table->longText('ios_app')->nullable();

            $table->text('value_added_tax')->nullable();
            $table->double('aed_to_usd', 20,10)->nullable();
            $table->double('commission', 20,10)->nullable();
            $table->unsignedInteger('created_at')->nullable();
            $table->unsignedInteger('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
