<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction', function (Blueprint $table) {
            $table->foreignId('member_id')->after('id')->constrained('members')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('admin_id')->after('member_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('admin_bank_id')->after('admin_id')->nullable()->constrained('user_banks')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('member_bank_id')->after('admin_bank_id')->nullable()->constrained('member_banks')->nullOnDelete()->cascadeOnUpdate();
            $table->string('type')->after('admin_id')->nullable();
            $table->text('remarks')->after('type')->nullable();
            $table->double('amount')->after('remarks')->default(0);
            $table->tinyInteger('status')->after('amount')->comment('0 pending, 1 approved, 2 rejected')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('_transaction', function (Blueprint $table) {
            //
        });
    }
}
