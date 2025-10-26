<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoundSnacksTable extends Migration{
    public function up()
    {
        Schema::create('round_snacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tasting_round_id')->constrained('tasting_rounds')->onDelete('cascade');
            $table->foreignId('snack_id')->constrained('snacks')->onDelete('cascade');
            $table->integer('sequence_order');
            $table->timestamps();
    
            $table->unique(['tasting_round_id', 'sequence_order']);
            $table->unique(['tasting_round_id', 'snack_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('round_snacks');
    }
}