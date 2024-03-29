<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('track_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('author');
            $table->string('description');
            $table->enum('difficulty', ['easy', 'medium', 'hard']);
            $table->string('attachment')->nullable();
            $table->string('external_resource')->nullable();
            $table->float('points');
            $table->integer('max_tries');
            $table->boolean('requires_judge');
            $table->string('solution')->nullable();
            $table->boolean('is_locked')->default(false);
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
        Schema::dropIfExists('challenges');
    }
};
