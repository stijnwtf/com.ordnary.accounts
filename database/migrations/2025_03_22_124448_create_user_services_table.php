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
        Schema::create('user_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('client_id');
            $table->timestamp('first_login')->useCurrent();
            $table->timestamp('last_used')->useCurrent();
            $table->enum('status', ['active', 'revoked'])->default('active');
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate entries
            $table->unique(['user_id', 'client_id']);
            
            // Add foreign key to clients table
            $table->foreign('client_id')
                ->references('client_id')
                ->on('clients')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_services');
    }
};
