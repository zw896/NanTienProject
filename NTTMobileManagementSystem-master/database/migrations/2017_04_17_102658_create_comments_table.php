<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CteateCommentsTable
 */
class CreateCommentsTable extends Migration {
    const TABLE_NAME = 'comments';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('eid')->unsigned();
            $table->integer('uid')->unsigned();
            $table->text('content');
            $table->tinyInteger('rating');
            $table->boolean('display');
            $table->timestamps();

            $table->foreign('eid')
                ->references('id')->on('events')
                ->onDelete('cascade');

            $table->foreign('uid')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists(static::TABLE_NAME);
    }
}
