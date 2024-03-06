<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$path = 'app/locations/divisions.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }

}
