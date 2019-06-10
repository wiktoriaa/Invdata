<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('rooms')->insert([
    		'id'   => '1',
            'name' => '154',
        ]);

        DB::table('users')->insert([
            'name' 	   => 'admin',
            'email'    => 'admin@admin',
            'password' => bcrypt('password'),
            'room_id'  => '1',
            'is_super' => '1',
        ]);
        
    }
}