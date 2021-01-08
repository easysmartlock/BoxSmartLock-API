<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = new User();
        $user->nom = 'admin';
        $user->prenom = 'admin';
        $user->email = 'easysmartlock@gmail.com';
        $user->role = User::ADMIN;
        $user->password = Hash::make('123456789abcdef##');
        $user->is_active = true;
        $user->save();
    }
}
