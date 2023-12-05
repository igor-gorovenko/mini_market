<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File as File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Intervention\Image\ImageManagerStatic as Image;
use TCPDF;

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

        $directory = storage_path('app/public/uploaded_files');
        File::cleanDirectory($directory);

        for ($i = 0; $i < 10; $i++) {
            $fileName = $faker->word;
            $pdfPath = $directory . '/' . $fileName . '.pdf';
            $imagePath = $directory . '/' . $fileName . '.jpg';

            // Создать PDF
            $pdf = new TCPDF();
            $pdf->AddPage();
            $pdf->Cell(40, 10, "Hello World! This is $fileName");
            $pdf->Output($pdfPath, 'F');

            $colors = [
                '#d9534f', // Красный
                '#5bc0de', // Голубой
                '#5cb85c', // Зелёный
                '#f0ad4e', // Жёлтый
                '#8e44ad', // Фиолетовый
                '#e67e22', // Оранжевый
                '#16a085', // Тёмно-зелёный
                '#9b59b6', // Пурпурный
                '#3498db', // Тёмно-синий
            ];
            $randomColor = $colors[array_rand($colors)];

            // Создать изображение
            $image = Image::canvas(160, 160, $randomColor);

            // Добавить надпись
            $text = $fileName;
            $fontColor = '#000';
            $image->text($text, 80, 80, function ($font) use ($fontColor) {
                $font->color($fontColor);
                $font->size(48);
                $font->align('center');
                $font->valign('middle');
            });

            $image->save($imagePath);

            DB::table('files')->insert([
                'name' => $fileName,
                'description' => $faker->sentence,
                'thumbnail' => $imagePath,
                'path' => $pdfPath,
                'price' => $faker->randomFloat(2, 0, 100),
                'dates' => $faker->date(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
