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
        Schema::create('comments', function (Blueprint $table) {
            $table->id('id_comment');
            $table->text('comment');
            $table->string('author_comment');
            $table->unsignedBigInteger('article_comment');
            $table->timestamps();
            $table->foreign('article_comment')->references('id_article')->on('articles')
                    ->cascadeOnDelete();
            $table->foreign('author_comment')->references('name')->on('users')
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();
                    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
