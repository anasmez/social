<?php

use App\Models\Status;
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
        User::truncate();
        Status::truncate();

        factory(User::class)->create([
            'email'=>'anas@email.com',
            'name'=>'anaso'
        ]);
        factory(Status::class, 10)->create();
    }
}
