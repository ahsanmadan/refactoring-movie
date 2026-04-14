<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nama_kategori' => 'Action', 'keterangan' => 'Film Penuh Aksi & Laga'],
            ['nama_kategori' => 'Drama', 'keterangan' => 'Film Drama Penuh Emosi'],
            ['nama_kategori' => 'Comedy', 'keterangan' => 'Film Komedi Lucu'],
            ['nama_kategori' => 'Horror', 'keterangan' => 'Film Horor Menyeramkan'],
            ['nama_kategori' => 'Sci-Fi', 'keterangan' => 'Film Fiksi Ilmiah'],
            ['nama_kategori' => 'Romance', 'keterangan' => 'Film Romantis'],
        ];
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
