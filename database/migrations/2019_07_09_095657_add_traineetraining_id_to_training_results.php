<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTraineetrainingIdToTrainingResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_results', function (Blueprint $table) {
            $table->bigInteger('trainingtrainee_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_results', function (Blueprint $table) {
            $table->dropColumn('trainingtrainee_id');
        });
    }
}
