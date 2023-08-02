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
        Schema::create('post_edit_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constraint('posts')->onDelete('cascade');
            $table->foreignId('user_id')->constraint('users')->onDelete('cascade');
            $table->tinyInteger('evaluation')->default(1);
            $table->string('type');
            $table->text('content');
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
        Schema::dropIfExists('post_edit_requests');
    }
};
