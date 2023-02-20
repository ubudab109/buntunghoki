<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('fullname');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->string('registered_from')->nullable();
            $table->string('user_code')->unique()->nullable();
            $table->string('referral')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->string('level')->nullable();
            $table->boolean('is_loggedin')->default(0);
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
        Schema::dropIfExists('members');
    }
}
