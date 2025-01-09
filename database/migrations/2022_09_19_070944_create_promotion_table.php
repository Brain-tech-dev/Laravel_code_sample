<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('strategy_information_id');
            $table->string('Strategy_information_name');
            $table->string("promotion_date");
            $table->string("promotion_starttime");
            $table->string("promotion_endtime");
            $table->text("promotion_notification_discription");
            $table->timestamps();
            $table->foreign('strategy_information_id')->references('id')->on('strategy_information');




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion');
    }
}
