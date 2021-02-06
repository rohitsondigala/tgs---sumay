<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminUserRolesSeeder extends Seeder
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
                'title' => 'ADMIN',
                'role' => 1,
            ],
            [
                'title' => 'MODERATOR',
                'role' => 2,
            ],
            [
                'title' => 'PROFESSOR',
                'role' => 4,
            ],
            [
                'title' => 'STUDENT',
                'role' => 8,
            ],
        ];

        foreach ($arr as $list){
            if(user_roles()->where('title',$list['title'])->count() == 0){
                user_roles()->create($list);
            }
        }
    }
}
