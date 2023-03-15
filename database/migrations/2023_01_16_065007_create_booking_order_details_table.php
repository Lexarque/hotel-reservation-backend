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
        Schema::create('booking_order_details', function (Blueprint $table) {
            $table->id();
            $table->string('staying_period', 10);
            $table->integer('price');
            $table->foreignId('booking_order_id')->constrained('booking_orders');
            $table->foreignId('room_id')->constrained('hotel_rooms');
            $table->date('access_date');
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
        Schema::dropIfExists('booking_order_details');
    }
};
