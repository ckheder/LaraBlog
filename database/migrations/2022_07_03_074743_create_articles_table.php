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
        Schema::create('articles', function (Blueprint $table) {
            $table->id('id_article');
            $table->string('titre_article');
            $table->text('corps_article');
            $table->string('author');
            $table->string('tag');
            $table->timestamps();
            $table->fullText(['titre_article', 'corps_article'],'FTtitrebody');
            $table->foreign('tag')->references('nametags')->on('tags')
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
       
        Schema::dropIfExists('articles');
    }
};
