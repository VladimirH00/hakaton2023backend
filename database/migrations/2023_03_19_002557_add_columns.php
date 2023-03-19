<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('queue', function (Blueprint $table){
            $table->integer('percent')->nullable();
        });

        Schema::table('users', function (Blueprint $table){
            $table->string('path')->nullable();
        });

        Schema::create('other_users', function (Blueprint $table) {
            $table->id();
            $table->string('img_path', 255);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('queue_id')->nullable();
            $table->foreign('queue_id')
                ->references('id')
                ->on('queue')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('percent')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('other_users', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['queue_id']);
        });

        Schema::table('queue', function (Blueprint $table){
            $table->dropColumn('percent');
        });

        Schema::table('users', function (Blueprint $table){
            $table->dropColumn('path');
        });

        Schema::dropIfExists('other_users');
    }
}
