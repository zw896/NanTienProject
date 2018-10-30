<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUserPasswordResetTable
 */
class CreateUserPasswordResetTable extends Migration {
    const TABLE_NAME = 'user_password_reset';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('uid')->unsigned();
            $table->string('hash', 64);
            $table->timestamp('expire');

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
