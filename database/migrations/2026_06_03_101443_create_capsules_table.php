<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('capsules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('content');
            $table->datetime('unlock_date');
            $table->boolean('is_locked')->default(true);
            $table->enum('visibility', ['only_me', 'friends', 'public'])->default('only_me');
            $table->enum('status', ['draft','locked','unlocked','pending_review','approved','rejected'])->default('draft');
            $table->boolean('is_group')->default(false);
            $table->string('group_name')->nullable();
            $table->text('reject_reason')->nullable();
            $table->timestamp('sealed_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('capsules');
    }
};