<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdminUser();
        $this->createUser();
    }

    private function createAdminUser()
    {
        $user = factory(User::class)->make([
            'name' => 'admin',
            'type' => User::ADMIN_TYPE,
            'email' => 'admin@aparat.me',
            'mobile' => '+989362531818',
        ]);
        $user->save();
        $this->command->info('create admin user');
    }

    private function createUser()
    {
        $user = factory(User::class)->make([
            'name' => 'user',
            'email' => 'user1@aparat.me',
            'mobile' => '+989123456787'
        ]);
        $user->save();
        $this->command->info('create user');
    }
}
