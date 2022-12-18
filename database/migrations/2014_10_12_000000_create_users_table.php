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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('track_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('full_name')->unique();
            $table->integer('step')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->float('points')->nullable();
            $table->enum('role', ['participant', 'judge', 'admin']);
            $table->string('golden_ticket')->nullable();
            $table->boolean('is_member')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
