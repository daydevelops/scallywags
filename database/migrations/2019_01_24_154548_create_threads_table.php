<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('category_id');
			$table->unsignedInteger('best_reply_id')->nullable();
			$table->string('title');
			$table->string('slug')->unique();
			$table->text('body');
			$table->unsignedInteger('visits')->default(0);
            $table->timestamps();

			$table->foreign('best_reply_id')
			->references('id')
			->on('thread_replies')
			->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
