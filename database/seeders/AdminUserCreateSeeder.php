<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(\user()->where('username','admin_user')->count() <= 0){
            User::create([
                'name'=> 'Admin',
                'username'=> 'admin_user',
                'mobile'=> '9898989898',
                'email'=> 'admin@guruchela.com',
                'password'=> bcrypt('1234'),
                'country'=>'1',
                'state'=>'1',
                'city'=>'1',
                'role_uuid'=>user_roles()->where('title','ADMIN')->value('uuid')
            ]);
        }
    }
}
