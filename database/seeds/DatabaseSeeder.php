<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $toTruncate = ['users'];

    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($this->toTruncate as $table) {
            DB::table($table)->truncate();
        }

        // $this->call(ArticlesTableSeeder::class);
        // $this->call(StatusesTableSeeder::class);
        // $this->call(TagsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        
    }
}
