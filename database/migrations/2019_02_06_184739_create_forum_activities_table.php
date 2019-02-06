<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_activities', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('user_id')->index();
			$table->unsignedInteger('subject_id')->index();
			$table->string('type');
			$table->string('subject_type',50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forum_activities');
    }
}
