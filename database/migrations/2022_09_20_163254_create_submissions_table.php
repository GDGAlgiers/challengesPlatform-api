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
        Schema::create('submissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->foreignId('participant_id')->constrained("users")->onDelete('cascade');
            $table->foreignId('track_id')->constrained()->onDelete('cascade');
            $table->foreignId('judge_id')->nullable()->constrained("users")->onDelete('cascade');
            $table->string('attachment')->nullable();
            $table->enum('status', ['pending', 'judging', 'rejected', 'approved', 'canceled']);
            $table->float('assigned_points')->nullable();
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
        Schema::dropIfExists('submissions');
    }
};
