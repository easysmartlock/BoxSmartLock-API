<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableTelephoneAddExtraFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('telephones', function (Blueprint $table) {
            //
            $table->string('debut')->nullable();
            $table->string('fin')->nullable();
            $table->boolean('unlimited')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('telephones', function (Blueprint $table) {
            //
        });
    }
}
