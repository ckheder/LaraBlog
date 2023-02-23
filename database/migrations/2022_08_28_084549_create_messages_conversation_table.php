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

        Schema::create('conversations', function (Blueprint $table) {
            $table->id('id_conv');
            $table->string('user_one');
            $table->string('user_two');
            $table->boolean('is_visible_user_one')->default(1);
            $table->boolean('is_visible_user_two')->default(1);
            $table->foreign('user_one')->references('name')->on('users')
                    ->cascadeOnDelete();
            $table->foreign('user_two')->references('name')->on('users')
                    ->cascadeOnDelete();
            $table->timestamps();
        });


        Schema::create('messages', function (Blueprint $table) {
            $table->id('id_message');
            $table->string('author_message');
            $table->text('corps_message');
            $table->unsignedBigInteger('conversation');
            $table->foreign('author_message')->references('name')->on('users')
                    ->cascadeOnDelete();
            $table->foreign('conversation')->references('id_conv')->on('conversations')
                    ->cascadeOnDelete();
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
        Schema::dropIfExists('messages');
    }
};
