<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEasyTelephonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('easy_telephones', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('telephone')->nullable();
            $table->string('ordre');

            $table->unsignedBigInteger('easy_id')->nullable();
            $table->foreign('easy_id')->references('id')->on('easies')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('easy_telephones');
    }
}
