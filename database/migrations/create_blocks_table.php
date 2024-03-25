<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('blockade.blocks_table', 'blocks'), function (Blueprint $table) {
            $table->foreignId(config('blockade.blocker_foreign_key', 'blocker_id'))
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId(config('blockade.blocked_foreign_key', 'blocked_id'))
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamp('expires_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config(key: 'blockade.blocks_table', default: 'blocks'));
    }
};
