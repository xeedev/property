<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id')->index();
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->unsignedBigInteger('sold_by_user_id')->index()->nullable();
            $table->foreign('sold_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('sold_to_user_id')->index()->nullable();
            $table->foreign('sold_to_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('demand', 12, 2)->nullable();
            $table->decimal('sold_in', 12, 2)->nullable();
            $table->decimal('actual_commission_amount', 12, 2)->nullable();
            $table->decimal('commission_received', 12, 2)->nullable();
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
        Schema::dropIfExists('leads');
    }
}
