<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TraineeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('trainees')->insert([
            'name' => '李逸然',
            'password' => bcrypt('test'),
            'created_at' => Carbon::now(),
            'avatar' => 'https://img0.pconline.com.cn/pconline/test/2018/000133532/000133501/1906/20196/28/15616946234350790.jpg'
        ]);
    }
}
