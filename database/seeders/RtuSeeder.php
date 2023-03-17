<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Rtu;
use Illuminate\Support\Facades\Hash;

class RtuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        //
        $user = new Rtu();
        $user->nom = 'admin';
        $user->prenom = 'admin';
        $user->email = 'easysmartlock@gmail.com';
        $user->password = Hash::make('123456789abcdef##');
        $user->save();
    }
}
