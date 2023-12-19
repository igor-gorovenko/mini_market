<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File as File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Faker\Factory as Faker;
use Intervention\Image\ImageManagerStatic as Image;
use TCPDF;


class FilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        DB::table('files')->truncate();

        $baseDirectory = storage_path('app/public/');
        $pdfDirectory = $baseDirectory . '/uploaded_files/pdf';
        $imageDirectory = $baseDirectory . '/uploaded_files/images';

        // Очистить папки перед заполнением
        File::cleanDirectory($pdfDirectory);
        File::cleanDirectory($imageDirectory);

        // Создать подпапки, если они не существуют
        File::makeDirectory($pdfDirectory, 0777, true, true);
        File::makeDirectory($imageDirectory, 0777, true, true);

        for ($i = 0; $i < 4; $i++) {
            $fileName = $faker->words(rand(2, 3), true);
            $slug = Str::slug($fileName, '-');
            $pdfPath = $pdfDirectory . '/' . $fileName . '.pdf';
            $imagePath = $imageDirectory . '/' . $fileName . '.jpg';

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
                'thumbnail' => str_replace($baseDirectory . '/', '', $imagePath),
                'path' => str_replace($baseDirectory . '/', '', $pdfPath),
                'price' => rand(0, 10),
                'dates' => $faker->date(),
                'slug' => $slug,
                'created_at' => now(),
                'updated_at' => now(),
                'payment_status' => 'unpaid',

            ]);
        }
    }
}
