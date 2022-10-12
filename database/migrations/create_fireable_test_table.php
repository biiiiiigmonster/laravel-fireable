<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->default('');
            $table->string('password')->default('');
            $table->tinyInteger('age')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->bigInteger('country_id')->default(0);
            $table->bigInteger('supplier_id')->default(0);
            $table->timestamps();
        });
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->default(0);
            $table->string('phone_number')->default('');
            $table->timestamps();
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
        Schema::dropIfExists('phones');
    }
};
