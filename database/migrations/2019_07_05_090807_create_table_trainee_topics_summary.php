<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTraineeTopicsSummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainee_topics_summary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('trainee_id')->index();
            $table->bigInteger('topic_id')->index();
            $table->integer('correct_count');
            $table->integer('fail_count');
            $table->boolean('recent_failed');
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
        Schema::dropIfExists('trainee_topics_summary');
    }
}
