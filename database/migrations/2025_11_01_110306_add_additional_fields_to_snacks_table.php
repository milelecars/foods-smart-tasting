<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('snacks', function (Blueprint $table) {
            $table->text('ingredients')->nullable()->after('description');
            $table->string('origin')->nullable()->after('ingredients');
            $table->string('shelf_life')->nullable()->after('origin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('snacks', function (Blueprint $table) {
            $table->dropColumn(['ingredients', 'origin', 'shelf_life']);
        });
    }
};
