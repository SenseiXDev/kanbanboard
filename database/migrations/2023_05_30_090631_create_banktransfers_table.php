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
        Schema::create('banktransfers', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id');
            $table->string('order_id');
            $table->integer('amount');
            $table->string('status')->default(1);
            $table->string('receipt')->nullable();
            $table->date('date');
            $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('banktransfers');
    }
};
