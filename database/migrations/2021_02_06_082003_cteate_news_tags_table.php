<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CteateNewsTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_tag', function (Blueprint $table) {
            $table->bigInteger('news_id')->unsigned();;
            $table->foreign('news_id')
                ->references('id')
                ->on('news')
                ->onDelete('cascade');
            $table->bigInteger('tag_id')->unsigned();;
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_tag');
    }
}
