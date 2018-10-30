<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration {
    const TABLE_NAME = 'messages';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('type');
            $table->string('title', 250);
            $table->string('content', 1024);
            $table->string('url', 256);
            $table->boolean('viewed');
            $table->timestamps();
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
