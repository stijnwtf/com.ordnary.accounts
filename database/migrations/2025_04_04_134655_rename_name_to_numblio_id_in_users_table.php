<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // First create the new UUID column
            $table->uuid('numblio_id')->after('id')->nullable();
            
            // Generate UUIDs for existing users
            DB::statement('UPDATE users SET numblio_id = UUID()');
            
            // Make the column unique and non-nullable after populating it
            $table->uuid('numblio_id')->nullable(false)->unique()->change();
            
            // Drop the old 'name' column
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Re-add the name column
            $table->string('name')->after('id')->nullable();
            
            // Try to reconstruct name from first_name and last_name if they exist
            if (Schema::hasColumn('users', 'first_name') && Schema::hasColumn('users', 'last_name')) {
                DB::statement("UPDATE users SET name = CONCAT(first_name, ' ', last_name)");
            }
            
            // Make name non-nullable after populating it
            $table->string('name')->nullable(false)->change();
            
            // Drop the numblio_id column
            $table->dropColumn('numblio_id');
        });
    }
};
