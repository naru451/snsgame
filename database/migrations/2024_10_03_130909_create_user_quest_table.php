<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserQuestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_quest', function (Blueprint $table) {
            $table->string('user_id',37)->collation('utf8_unicode_ci');
            $table->unsignedInteger('quest_id')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('clear_time')->default(0);
            $table->timestamps();
            $table->primary('user_id','quest_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_quest');
    }
}
