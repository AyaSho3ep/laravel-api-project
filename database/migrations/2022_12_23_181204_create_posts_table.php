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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constraint('categories')->onDelete('cascade');
            $table->foreignId('postClassification_id')->constraint('post_classifications')->onDelete('cascade');
            $table->foreignId('user_id')->constraint('users')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->text('sources');
            $table->text('evaluation');
            $table->text('proof');
            $table->string('media')->nullable();
            $table->integer('views');
            $table->tinyInteger('trending')->default(0);
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
        Schema::dropIfExists('posts');
    }
};
