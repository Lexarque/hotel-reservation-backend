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
        Schema::create('booking_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->unique();
            $table->date('checkin_date');
            $table->date('checkout_date');
            $table->integer('room_count');
            $table->foreignId('user_id')->constrained('users'); 
            $table->foreignId('room_type_id')->constrained('hotel_room_types');
            $table->string('status', 20)->default('booked')->comment('booked | staying | checked_out');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_orders');
    }
};
