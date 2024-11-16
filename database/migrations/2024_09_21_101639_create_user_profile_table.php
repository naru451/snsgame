<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profile', function (Blueprint $table) {
        $table->string('user_id',37)->collation('utf8_unicode_ci');
	    $table->string('user_name',32)->collation('utf8_unicode_ci');
	    $table->unsignedInteger('crystal')->default(0);
	    $table->unsignedInteger('crystal_free')->default(0);
	    $table->unsignedInteger('friend_coin')->default(0);
        $table->unsignedInteger('tutorial_progress')->default(0);
        $table->timestamps();
        $table->primary('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profile');
    }
}
