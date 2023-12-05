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
                '#ff0000', // Красный
                '#00ff00', // Зелёный
                '#0000ff', // Синий
                '#ffff00', // Жёлтый
                '#ff00ff', // Фиолетовый
                '#ffa500', // Оранжевый
                '#008080', // Тёмно-зелёный
                '#800080', // Пурпурный
                '#008000', // Тёмно-синий
            ];
            $randomColor = $colors[array_rand($colors)];

            // Создать изображение
            $image = Image::canvas(320, 320, $randomColor);

            // Добавить надпись
            $text = $fileName;
            $fontColor = '#000';
            $image->text($text, 160, 160, function ($font) use ($fontColor) {
                $font->color($fontColor);
                $font->size(24);
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
