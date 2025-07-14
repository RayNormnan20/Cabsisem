<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::where('email', 'john.doe@helper.app')->count() == 0) {
            $user = User::create([
                'name' => 'John DOE',
                'celular' => '999111222',
                'email' => 'john.doe@helper.app',
                'password' => bcrypt('Passw@rd'),
                'email_verified_at' => now()
            ]);
            
            $user = User::create([
                'name' => 'Juan',
                'celular' => '222333444',
                'email' => 'juan@helper.app',
                'password' => bcrypt('Passw@rd'),
                'email_verified_at' => now()
            ]);

            $user = User::create([
                'name' => 'Sebas',
                'celular' => '888777333',
                'email' => 'sebas@gmail.com',
                'password' => bcrypt('Passw@rd'),
                'email_verified_at' => now()
            ]);

            $user->creation_token = null;
            $user->save();
        }
    }
}
