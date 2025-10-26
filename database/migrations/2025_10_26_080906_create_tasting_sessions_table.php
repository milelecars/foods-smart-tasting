<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTastingSessionsTable extends Migration
{
    public function up() {
        Schema::create('tasting_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('tasting_round_id')->constrained('tasting_rounds');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->timestamps();

            $table->unique(['user_id', 'tasting_round_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('tasting_sessions');
    }
};