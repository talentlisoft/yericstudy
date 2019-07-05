<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTraineeTrainings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainee_trainings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('trainee_id');
            $table->bigInteger('training_id');
            $table->integer('status')->default(0)->comment('状态');
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
        Schema::dropIfExists('trainee_trainings');
    }
}
