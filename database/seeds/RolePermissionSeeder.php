<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role

        DB::table('roles')->insert([
            'slug' => 'reader',
            'title' => 'Reader',
            'description' => 'Library\' normal reader user.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('roles')->insert([
            'slug' => 'manager',
            'title' => 'Manager',
            'description' => 'Library\' manager.',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // Permission

        DB::table('permissions')->insert([
            'slug' => 'book-read',
            'title' => 'Book Read',
            'description' => 'Book api read access',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('permissions')->insert([
            'slug' => 'book-write',
            'title' => 'Book Write',
            'description' => 'Book api write access',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        // Role Permission

        DB::table('role_permission')->insert([
            'role_id' => 'reader',
            'permission_id' => 'book-read',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
