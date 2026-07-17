<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds optional "reflection quote" fields to posts, so a user
     * can attach an inspirational quote to a feed post the same
     * way they can attach one to a capsule.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('quote_text')->nullable()->after('caption');
            $table->string('quote_author')->nullable()->after('quote_text');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['quote_text', 'quote_author']);
        });
    }
};