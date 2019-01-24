<?php

use Illuminate\Database\Seeder;
use App\Event;
class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // create 2 events with 2 games each, with 2 users each
        for ($num_events=1;$num_events<3;$num_events++) {
            $randDay = rand(1,3)*60*60*24;
            $randHour = rand(-10,10)*60*60; // converted to seconds
            $randtime = date('c',time()+$randDay+$randHour);

            $event_id = Event::bookCourt($randtime);
            Event::bookCourt($randtime); // need to book the court twice

            $games = factory(App\Game::class,2)->create(['event_id'=>$event_id,'gamedate'=>$randtime]);
            foreach ($games as $game) {
                $randUsers = App\User::take(2)->inRandomOrder()->get();
                $randUser1 = $randUsers[0];
                $randUser2 = $randUsers[1];
                App\GameUser::create([
                    'game_id'=>$game->id,
                    'user_id'=>$randUser1->id,
                    'confirmed'=>1
                ]);
                App\GameUser::create([
                    'game_id'=>$game->id,
                    'user_id'=>$randUser2->id,
                    'confirmed'=>1
                ]);
            }
        }


        // create 1 event with 1 game with 1 user and 3 invites

        $randDay = rand(1,3)*60*60*24;
        $randHour = rand(-10,10)*60*60; // converted to seconds
        $randtime =  date('c',time()+$randDay+$randHour);

        $event_id = Event::bookCourt($randtime);

        $games = factory(App\Game::class,1)->create(['event_id'=>$event_id]);
        foreach ($games as $game) {
            $randUsers = App\User::take(4)->inRandomOrder()->get();
            $randUser1 = $randUsers[0];
            $randUser2 = $randUsers[1];
            $randUser3 = $randUsers[2];
            $randUser4 = $randUsers[3];
            App\GameUser::create([
                'game_id'=>$game->id,
                'user_id'=>$randUser1->id,
                'confirmed'=>1
            ]);
			App\GameUser::create([
                'game_id'=>$game->id,
                'user_id'=>$randUser2->id,
                'confirmed'=>1
            ]);
			App\GameUser::create([
                'game_id'=>$game->id,
                'user_id'=>$randUser3->id,
                'confirmed'=>1
            ]);
			App\GameUser::create([
                'game_id'=>$game->id,
                'user_id'=>$randUser4->id,
                'confirmed'=>1
            ]);
        }
    }
}
