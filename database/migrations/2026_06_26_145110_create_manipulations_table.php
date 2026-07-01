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
        Schema::create('manipulations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->uuid('session_id')->nullable()->index();
            $table->string('model_name');
            $table->string('color', 9)->default('#8b5cf6');
            $table->decimal('scale', 5, 2)->default(1.00);
            $table->decimal('position_x', 8, 2)->default(0);
            $table->decimal('position_y', 8, 2)->default(0);
            $table->decimal('position_z', 8, 2)->default(0);
            $table->decimal('rotation_x', 8, 2)->default(0);
            $table->decimal('rotation_y', 8, 2)->default(0);
            $table->decimal('rotation_z', 8, 2)->default(0);
            $table->decimal('roughness', 4, 2)->default(0.70);
            $table->decimal('metalness', 4, 2)->default(0.10);
            $table->string('style', 20)->default('solid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manipulations');
    }
};
