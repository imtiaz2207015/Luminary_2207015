<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── comments: make capsule_id nullable, add post_id ──
        DB::unprepared('
            ALTER TABLE comments
                MODIFY capsule_id BIGINT UNSIGNED NULL,
                ADD COLUMN post_id BIGINT UNSIGNED NULL AFTER capsule_id,
                ADD CONSTRAINT comments_post_id_foreign
                    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE SET NULL
        ');

        // ── reactions: drop the actual FK (reactions_user_id_foreign),
        //   then drop the unique index, then re-add everything ──
        DB::unprepared('ALTER TABLE reactions DROP FOREIGN KEY reactions_user_id_foreign');
        DB::unprepared('ALTER TABLE reactions DROP INDEX reactions_user_id_capsule_id_unique');
        DB::unprepared('
            ALTER TABLE reactions
                MODIFY capsule_id BIGINT UNSIGNED NULL,
                ADD COLUMN post_id BIGINT UNSIGNED NULL AFTER capsule_id,
                ADD CONSTRAINT reactions_user_id_foreign
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                ADD CONSTRAINT reactions_post_id_foreign
                    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE SET NULL
        ');
    }

    public function down(): void
    {
        DB::unprepared('ALTER TABLE comments DROP FOREIGN KEY comments_post_id_foreign');
        DB::unprepared('ALTER TABLE comments DROP COLUMN post_id');

        DB::unprepared('ALTER TABLE reactions DROP FOREIGN KEY reactions_post_id_foreign');
        DB::unprepared('ALTER TABLE reactions DROP COLUMN post_id');
    }
};