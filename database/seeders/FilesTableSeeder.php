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
                '#ff8c94', // Мягкий розовый
                '#afdceb', // Светло-голубой
                '#a9dfbf', // Светло-зелёный
                '#fbdca9', // Светло-жёлтый
                '#d8a8e8', // Бледно-фиолетовый
                '#f1c395', // Персиковый
                '#a3e4d7', // Бледно-зелёный
                '#d3b8d8', // Бледно-пурпурный
                '#a9ccec', // Светло-синий
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
