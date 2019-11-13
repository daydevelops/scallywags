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
        // create 1 conversation for the admin and one other user
        $convo = factory('App\Conversation')->create([
            'channel_name' => 'testing'
        ]);

        $admin = App\User::where('is_admin',1)->first();
        $non_admin = App\User::where('is_admin',0)->first();

        $convo->users()->attach([$admin->id,$non_admin->id]);
        
        // 10 msgs each in alternating order
        for ($i=0;$i<10;$i++) {
            factory('App\Message')->create([
                'user_id'=>$admin->id,
                'conversation_id' => $convo->id
            ]);
            factory('App\Message')->create([
                'user_id'=>$non_admin->id,
                'conversation_id' => $convo->id
            ]);
        }
    }
}
