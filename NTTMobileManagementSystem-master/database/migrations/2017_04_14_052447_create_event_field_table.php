<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateEventFieldTable
 */
class CreateEventFieldTable extends Migration {
    const TABLE_NAME = 'event_field';

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
            $table->text('field_value');
            $table->integer('field_define')->unsigned();

            $table->foreign('eid')
                ->references('id')->on('events')
                ->onDelete('cascade');

            $table->foreign('field_define')
                ->references('id')->on('event_field_definition')
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
