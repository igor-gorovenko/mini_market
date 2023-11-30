<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File as File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
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

        $directory = storage_path('app/uploaded_files');
        File::cleanDirectory($directory);

        for ($i = 0; $i < 10; $i++) {
            $pdfPath = $directory . '/' . $faker->word . '.pdf';

            // Создать PDF
            $pdf = new TCPDF();

            $pdf->AddPage();
            $pdf->Cell(40, 10, 'Hello World!');

            $pdf->Output($pdfPath, 'F');

            DB::table('files')->insert([
                'name' => $faker->word,
                'description' => $faker->sentence,
                'thumbnail' => $pdfPath,
                'path' => $pdfPath,
                'price' => $faker->randomFloat(2, 0, 100),
                'dates' => $faker->date(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
