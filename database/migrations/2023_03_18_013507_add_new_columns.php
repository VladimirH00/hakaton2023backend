<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 100);
        });

        Schema::table('users', function (Blueprint $table){
            $table->string('surname', 100)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('patronymic', 100)->nullable();
            $table->integer('inn')->nullable();
            $table->dateTime('date_beth')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('queue', function (Blueprint $table) {
            $table->id();
            $table->string('img_path', 255);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
        });

        Schema::table('dataset', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('queue', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('queue');
        Schema::dropIfExists('cities');
        Schema::table('users', function (Blueprint $table){
            $table->dropColumn('surname');
            $table->dropColumn('first_name');
            $table->dropColumn('patronymic');
            $table->dropColumn('inn');
            $table->dropColumn('date_beth');
            $table->dropColumn('city_id');
        });
    }
}
