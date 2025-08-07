<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {Schema::create('messages', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('sender_id'); // ID của người gửi (admin/user)
        $table->unsignedBigInteger('receiver_id'); // ID của người nhận (admin/user)
        $table->text('content');
        $table->timestamps();
    
        $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
