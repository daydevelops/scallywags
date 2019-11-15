<?php

use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 2; $i++) {
            // create 1 conversation for the admin and one other user
            $convo = factory('App\Conversation')->create([
                'channel_name' => 'testing'
            ]);

            $admin = App\User::where('is_admin', 1)->first();
            $non_admin = App\User::where('is_admin', 0)->get()[$i];

            $convo->users()->attach([$admin->id, $non_admin->id]);

            // 10 msgs each in alternating order
            for ($j = 0; $j < 10; $j++) {
                factory('App\Message')->create([
                    'user_id' => $admin->id,
                    'conversation_id' => $convo->id
                ]);
                factory('App\Message')->create([
                    'user_id' => $non_admin->id,
                    'conversation_id' => $convo->id
                ]);
            }
        }
    }
}
