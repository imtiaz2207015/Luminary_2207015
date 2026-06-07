<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('friendships', function (Blueprint $table) {
            $table->unique(['user_id', 'friend_id']);
        });
    }
    public function down(): void {
        Schema::table('friendships', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'friend_id']);
        });
    }
};