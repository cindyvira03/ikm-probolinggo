<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('12345678'),
        //     'role' => 'Admin',
        // ]);

        // // Buatkan kategori => Makanan dan Minuman, Fashion, Kerajinan
        // Kategori::create([
        //     'nama_kategori' => 'Makanan dan Minuman',
        //     'slug' => 'makanan-dan-minuman',
        // ]);
        // Kategori::create([
        //     'nama_kategori' => 'Fashion',
        //     'slug' => 'fashion',
        // ]);
        // Kategori::create([
        //     'nama_kategori' => 'Kerajinan',
        //     'slug' => 'kerajinan',
        // ]);

        User::find(16)->update([
            'password' => bcrypt('12345678'),
        ]);
    }
}
