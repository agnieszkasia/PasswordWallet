<?php

use App\UserFunction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        UserFunction::create([
            'id' => '1',
            'function_name' => 'create',
            'description' => 'function to create web password'
            ]);

        UserFunction::create([
            'id' => '2',
            'function_name' => 'update',
            'description' => 'function to update web password'
        ]);

        UserFunction::create([
            'id' => '3',
            'function_name' => 'delete',
            'description' => 'function to delete web password'
        ]);

        UserFunction::create([
            'id' => '4',
            'function_name' => 'restore',
            'description' => 'function to restore web password'
        ]);
    }
}
