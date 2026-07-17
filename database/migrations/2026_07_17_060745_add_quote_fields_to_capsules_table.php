<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds optional "reflection quote" fields to capsules.
     * A user may attach an inspirational quote (fetched via the
     * ZenQuotes API) when sealing a capsule for their future self.
     */
    public function up(): void
    {
        Schema::table('capsules', function (Blueprint $table) {
            $table->string('quote_text')->nullable()->after('content');
            $table->string('quote_author')->nullable()->after('quote_text');
        });
    }

    public function down(): void
    {
        Schema::table('capsules', function (Blueprint $table) {
            $table->dropColumn(['quote_text', 'quote_author']);
        });
    }
};