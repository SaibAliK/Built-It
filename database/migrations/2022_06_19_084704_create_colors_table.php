<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id');
            $table->string('heading_color')->nullable()->default('#222');
            $table->string('text_color')->nullable()->default('#a68f7f');
            $table->string('icons_color')->nullable()->default('#fefdfd');
            $table->string('background_color')->nullable()->default('#ffffff');
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colors');
    }
}
