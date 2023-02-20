<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->comment('registered member')->constrained('members')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('referral_id')->comment('referrer')->constrained('members')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('referral_code')->nullable();
            $table->double('commision')->default(0);
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
        Schema::dropIfExists('referral_members');
    }
}
