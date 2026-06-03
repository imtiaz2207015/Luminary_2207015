<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('capsule_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['inspired', 'goals', 'proud']);
            $table->unique(['user_id', 'capsule_id']);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('reactions');
    }
};