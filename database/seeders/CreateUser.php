<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Leo\Users\Models\User;
use Illuminate\Support\Facades\Hash;
class CreateUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=1; $i <21 ; $i++) {
            $data=[
                'name'=>'User '.$i,
                'password'=>Hash::make(111),
                'email'=>'user'.$i.'@gmail.com',
                'idRole'=>2,
                'created_at'=>now()
            ];
            User::create($data);
        }
    }
}
