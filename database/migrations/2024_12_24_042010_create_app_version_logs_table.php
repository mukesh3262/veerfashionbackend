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
        Schema::create('app_version_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('android_version');
            $table->tinyInteger('is_android_force_update')
                ->unsigned()->default(0);
            $table->unsignedInteger('ios_version');
            $table->tinyInteger('is_ios_force_update')
                ->unsigned()->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_version_logs');
    }
};
