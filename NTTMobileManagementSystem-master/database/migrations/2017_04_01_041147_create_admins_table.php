<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAdminsTable
 */
class CreateAdminsTable extends Migration {
    const TABLE_NAME = 'admins';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(static::TABLE_NAME, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 30);
            $table->string('password');
            $table->string('email', 50)->unique();
            $table->string('remember_token')->nullable();
            $table->timestamp('last_login');
            $table->timestamp('banned_at')->nullable();
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
