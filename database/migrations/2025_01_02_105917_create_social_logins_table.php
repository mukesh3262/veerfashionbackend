<?php

declare(strict_types=1);

use App\Enums\SocialTypeEnum;
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
        Schema::create('social_logins', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained('users')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('social_id')->nullable();
            $table->tinyInteger('type')->comment($this->getCommentForType());
            $table->json('data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_logins');
    }

    protected function getCommentForType()
    {
        return implode(', ', array_map(
            fn ($case, $index) => ($index + 1).' - '.$case->value,
            SocialTypeEnum::cases(),
            array_keys(SocialTypeEnum::cases())
        ));
    }
};
