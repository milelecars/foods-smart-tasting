<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateReviewsTable extends Migration{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('tasting_sessions')->onDelete('cascade');
            $table->foreignId('snack_id')->constrained('snacks');
            $table->integer('taste_rating')->unsigned();
            $table->integer('texture_rating')->unsigned();
            $table->integer('appearance_rating')->unsigned();
            $table->integer('overall_rating')->unsigned();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['session_id', 'snack_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}   