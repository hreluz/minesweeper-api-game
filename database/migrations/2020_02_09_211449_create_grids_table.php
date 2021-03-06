<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGridsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grids', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('x');
            $table->tinyInteger('y');
            $table->tinyInteger('difficulty');
            $table->tinyInteger('free_spaces')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->json('grid');
            $table->dateTime('started');
            $table->dateTime('finalized')->nullable();
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
        Schema::dropIfExists('grids');
    }
}
