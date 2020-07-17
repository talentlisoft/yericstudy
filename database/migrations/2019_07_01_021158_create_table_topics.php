<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('level', ['PSCHOOL', 'JHSCHOOL', 'SHSCHOOL', 'COLLEDGE'])->comment('学校等级');
            $table->enum('grade', ['1', '2', '3', '4', '5', '6']);
            $table->bigInteger('course_id')->index()->comment('学科ID');
            $table->text('question')->comment('题目');
            $table->string('answer', 200)->nullable()->comment('答案');
            $table->index(['level', 'grade']);
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
        Schema::dropIfExists('topics');
    }
}
