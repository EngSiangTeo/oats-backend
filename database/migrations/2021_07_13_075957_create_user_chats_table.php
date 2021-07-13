<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_chats', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('username')->references('username')->on('users')->constrained();
            $table->foreignId('chat_id')->constrained();
            $table->foreignId('listing_id')->constrained();
            $table->timestamps();
            $table->primary(['username', 'chat_id', 'listing_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_chats');
    }
}
