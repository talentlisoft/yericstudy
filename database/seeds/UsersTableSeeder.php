<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Lisoft',
            'email' => 'talentlisoft@gmail.com',
            'password' => bcrypt('test'),
            'created_at' => Carbon::now()
        ]);
    }
}
