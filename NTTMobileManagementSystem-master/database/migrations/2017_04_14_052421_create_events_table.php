<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateEventsTable
 */
class CreateEventsTable extends Migration {
    const TABLE_NAME = 'events';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('eid')->unique();
            $table->string('type', 50);
            $table->string('title', 512);
            $table->longtext('body');
            $table->string('author', 100);
            $table->integer('view');
            $table->boolean('sticky');
            $table->integer('priority');
            $table->boolean('featured');
            $table->boolean('pushed');
            $table->boolean('published');
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
