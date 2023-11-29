<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as File;
use Faker\Factory as Faker;


class FilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        // Очистить таблицу перед заполнением
        DB::table('files')->truncate();

        $directory = storage_path('app/uploaded_files');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0777, true, true);
        }

        for ($i = 0; $i < 10; $i++) {
            $thumbnailPath = $directory . '/' . $faker->word . '.jpg'; // или другое расширение по вашему выбору

            // Сохраняем случайное изображение в директорию
            copy($faker->imageUrl(), $thumbnailPath);


            DB::table('files')->insert([
                'name' => $faker->word,
                'description' => $faker->sentence,
                'thumbnail' => $thumbnailPath,
                'path' => $directory . '/' . $faker->word . '.txt', // или другое расширение файла по вашему усмотрению
                'price' => $faker->randomFloat(2, 0, 100),
                'dates' => $faker->date(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
