<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Mehnat\User\Entities\Role;
use Mehnat\User\Entities\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'admin', 'display_name' => 'Admin', 'description' => 'Admin'],
            ['name' => 'user', 'display_name' => 'User', 'description' => 'User']
        ];
        foreach($roles as $role){
            Role::updateOrCreate(
                ['name' => $role['name']],
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description']
                ]
                );
        }
         $role = Role::where('name', 'admin')->first();
         $user = new User;
         $user->username = 'admin';
         $user->password = '123456';
         $user->fullname = 'Aдмин';
         $user->save();
         $user->attachRole($role);

//         factory(User::class, 20)->create();
    }
}
