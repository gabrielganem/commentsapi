<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments')->insert([   
        [
            'name' => 'John Smith',
             'message' => 'Hi Jane, please read this post.',
             'parent_id' => null,
        ],
        [
            'name' => 'The Hound',
             'message' => "Im gonna have to eat every chicken in this room!",
             'parent_id' => null,
        ],
    ]);

        
    }
}
