<?php

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
        Schema::create('refresh_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('client_id');
            $table->string('token')->unique();
            $table->string('original_jti')->comment('JWT ID of the original token');
            $table->timestamp('expires_at');
            $table->boolean('used')->default(false);
            $table->timestamps();
            
            // Add indexes for faster lookups
            $table->index(['token', 'client_id']);
            $table->index('original_jti');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refresh_tokens');
    }
};
