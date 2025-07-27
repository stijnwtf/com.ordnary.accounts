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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('owner_email')->nullable()->after('name');
            $table->string('owner_name')->nullable()->after('owner_email');
            $table->unsignedBigInteger('owner_user_id')->nullable()->after('owner_name');
            $table->text('description')->nullable()->after('owner_user_id');
            
            // Add foreign key constraint
            $table->foreign('owner_user_id')->references('id')->on('users')->onDelete('set null');
            
            // Add index for performance
            $table->index('owner_email');
            $table->index('owner_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['owner_user_id']);
            $table->dropIndex(['owner_email']);
            $table->dropIndex(['owner_user_id']);
            $table->dropColumn(['owner_email', 'owner_name', 'owner_user_id', 'description']);
        });
    }
};
