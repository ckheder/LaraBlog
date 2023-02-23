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
        Schema::create('recommends', function (Blueprint $table) {
            $table->id('id_recommends');
            $table->string('user_recommends');
            $table->unsignedBigInteger('article_recommends');

            $table->foreign('user_recommends')->references('name')->on('users')
                                                                    ->cascadeOnDelete();

            $table->foreign('article_recommends')->references('id_article')->on('articles')
                                                                    ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recommends');
    }
};
