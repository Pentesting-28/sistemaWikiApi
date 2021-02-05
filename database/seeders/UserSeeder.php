<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /*UserAdmin*/
        //||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
        $userAdmin = User::where('email','admin@email.com')->first();

        if($userAdmin){$userAdmin->delete();}

        $userAdmin = User::create([
	        'name' => 'admin',
	        'email' => 'admin@cantv.com.ve',
            'email_verified_at' => now(),
	        'password' => Hash::make('123456')
        ]);


        /*UserRoot*/
        //||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
        $userRoot = User::where('email','root@email.com')->first();

        if($userRoot){$userRoot->delete();}

        $userRoot = User::create([
	        'name' => 'root',
	        'email' => 'root@cantv.com.ve',
            'email_verified_at' => now(),
	        'password' => Hash::make('123456')
        ]);
    }
}
