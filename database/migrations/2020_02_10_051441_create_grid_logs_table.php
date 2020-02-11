<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGridLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grid_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('grid');
            $table->tinyInteger('x');
            $table->tinyInteger('y');
            $table->tinyInteger('cells_opened')->default(0);
            $table->unsignedBigInteger('grid_id')->nullable();

            $table->foreign('grid_id')
                ->references('id')
                ->on('grids')
                ->onDelete('CASCADE');

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
        Schema::dropIfExists('grid_logs');
    }
}
