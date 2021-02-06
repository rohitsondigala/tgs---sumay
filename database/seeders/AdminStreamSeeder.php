<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminStreamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            [
                'title' => 'Science',
                'slug' => 'science',
                'is_standard' => 0,
                'status' =>1
            ],
            [
                'title' => 'Commerce',
                'slug' => 'commerce',
                'is_standard' => 0,
                'status' =>1
            ],
            [
                'title' => 'Arts',
                'slug' => 'arts',
                'is_standard' => 0,
                'status' =>1
            ],
        ];

        foreach ($arr as $list){
            if(streams()->where('title',$list['title'])->count() == 0){
                streams()->create($list);
            }
        }
    }
}
