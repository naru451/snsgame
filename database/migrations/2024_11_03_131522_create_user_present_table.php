<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPresentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_present', function (Blueprint $table) {
            $table->increments('present_id');
            $table->string('user_id',37)->collation('utf8_unicode_ci');
            $table->unsignedSmallInteger('item_type')->default(0);
            $table->unsignedInteger('item_count')->default(0);
            $table->string('description',32)->collation('utf8_unicode_ci');
            $table->timestamp('limited_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_present');
    }
}
