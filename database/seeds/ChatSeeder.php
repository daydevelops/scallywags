<?php

use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 2; $i++) {
            // create 1 chat for the admin and one other user
            $chat = factory('App\Chat')->create();

            $admin = App\User::where('is_admin', 1)->first();
            $non_admin = App\User::where('is_admin', 0)->get()[$i];

            $chat->users()->attach([$admin->id, $non_admin->id]);

            // 10 msgs each in alternating order
            for ($j = 0; $j < 10; $j++) {
                factory('App\Message')->create([
                    'user_id' => $admin->id,
                    'chat_id' => $chat->id
                ]);
                factory('App\Message')->create([
                    'user_id' => $non_admin->id,
                    'chat_id' => $chat->id
                ]);
            }
        }
    }
}
