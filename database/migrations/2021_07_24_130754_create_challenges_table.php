<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallengesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->integer('min_age');
            $table->integer('max_age');
            $table->integer('max_participants')->nullable();
            $table->string('reward');

            $table->foreignId('stock_image_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->string('video');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
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
        Schema::dropIfExists('challenges');
    }
}
