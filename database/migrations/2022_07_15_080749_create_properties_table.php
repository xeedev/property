<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_number')->nullable();
            $table->text('detail')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('location_id')->index()->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->unsignedBigInteger('block_id')->index()->nullable();
            $table->foreign('block_id')->references('id')->on('blocks')->onDelete('cascade');
            $table->unsignedBigInteger('sold_by_user_id')->index()->nullable();
            $table->foreign('sold_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('sold_to_user_id')->index()->nullable();
            $table->foreign('sold_to_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('demand', 12, 2)->nullable();
            $table->decimal('negotiated_price', 12, 2)->nullable();
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
        Schema::dropIfExists('properties');
    }
}
