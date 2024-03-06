<?php

namespace Database\Seeders;
use App\Models\Admins;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;
use Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $supperadmin = Admins::where('email','concaveit@gmail.com')->first();
        if(is_null($supperadmin)){
            $user = new Admins();
            $user->name = 'concaveit';
            $user->email = 'concaveit@gmail.com';
            $user->password = Hash::make('password');
            $user->phone = '01711158729';
            $user->assignRole('superadmin');
            $user->save();
        }
		
		if (!Schema::hasTable('divisions')) {
			$path = 'app/locations/divisions.sql';
			$sql = file_get_contents($path);
			DB::unprepared($sql);
		}
		if (!Schema::hasTable('districts')) {
			$path = 'app/locations/districts.sql';
			$sql = file_get_contents($path);
			DB::unprepared($sql);
		}
			
			
		if (!Schema::hasTable('upazilas')) {
			$path = 'app/locations/upazilas.sql';
			$sql = file_get_contents($path);
			DB::unprepared($sql);
		}
			
			
		if (!Schema::hasTable('unions')) {
			$path = 'app/locations/unions.sql';
			$sql = file_get_contents($path);
			DB::unprepared($sql);
		}

			
		
    }
}
