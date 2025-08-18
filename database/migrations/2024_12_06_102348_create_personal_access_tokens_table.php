<?php

declare(strict_types=1);

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
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('access_token', 64)->unique();
            $table->timestamp('access_token_expired_at')->nullable();
            $table->string('refresh_token', 64)->unique();
            $table->timestamp('refresh_token_expired_at')->nullable();
            $table->string('device_name');
            $table->integer('device_type')->comment('1: ios, 2: android, 3:web');
            $table->string('device_id')->comment('Device unique id');
            $table->string('ip');
            $table->string('fcm_key')->nullable()->comment('Device firebase token');
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
