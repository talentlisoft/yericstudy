<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('courses')->insert([
            ['name' => '语文', 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()],
            ['name' => '英语', 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()],
            ['name' => '数学', 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()]
        ]);
    }
}
