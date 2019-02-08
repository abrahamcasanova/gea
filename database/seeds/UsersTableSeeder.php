<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create default user
        $user = \App\User::create([
            'name' => 'admin',
            'email' => 'abrahamcasanovac@outlook.com',
            'password' => bcrypt('admin'),
            'avatar' => 'avatar.png'
        ]);
        // Assign admin role to default user
        $user->assignRole('admin');
        // Generate avatar to defautl user
        $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
        Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);
    }
}
