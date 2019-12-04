<?php

use Illuminate\Database\Seeder;
use App\Profile;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // $user = App\User::create([
        //        'name'           => "nooralamkhan",
        //        'email'          => "nooralamkhan@gmail.com",
        //        'password'       => bcrypt('testing321'),
        //        'remember_token' => str_random(10),
        // ]);
        //  $profile          = new Profile();
        //  $profile->user_id = $user->id;
        //  $profile->save();
         
      	 $this->call(UserTableSeeder::class);
        //  $this->call(PostsTableSeeder::class);
       

    }
}
