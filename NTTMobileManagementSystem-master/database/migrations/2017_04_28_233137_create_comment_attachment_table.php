<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCommentAttachmentTable
 */
class CreateCommentAttachmentTable extends Migration {
    const TABLE_NAME = 'comment_attachment';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('cid')->unsigned();
            $table->tinyInteger('type');
            $table->string('filename', 128);
            $table->integer('size');
            $table->timestamps();

            $table->foreign('cid')
                ->references('id')->on('comments')
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
