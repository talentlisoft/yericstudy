<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTrainingResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('trainingtopic_id')->index();
            $table->string('answer');
            $table->enum('status', ['PENDDING', 'CORRECT', 'WRONG']);
            $table->smallInteger('duration')->default(0);
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
        Schema::dropIfExists('training_results');
    }
}
