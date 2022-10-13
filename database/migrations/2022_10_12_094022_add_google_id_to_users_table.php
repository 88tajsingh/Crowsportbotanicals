<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) 
        {
            Schema::table('users', function (Blueprint $table) {
                $table->string('google_id')->nullable();
                $table->string('gender')->nullable();
                $table->integer('age')->nullable();
                $table->string('describes_you')->nullable();
                $table->string('allow_notification')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
