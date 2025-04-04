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
        // First add the column
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('numblio_id')->after('id')->nullable();
        });
        
        // Then update it with UUIDs
        DB::statement('UPDATE users SET numblio_id = UUID()');
        
        // Then make it required and unique
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('numblio_id')->nullable(false)->unique()->change();
        });
        
        // Finally drop the name column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the name column first
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id')->nullable();
        });
        
        // Populate it with data from first_name and last_name
        if (Schema::hasColumn('users', 'first_name') && Schema::hasColumn('users', 'last_name')) {
            DB::statement("UPDATE users SET name = CONCAT(first_name, ' ', last_name)");
        }
        
        // Make it required
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
        });
        
        // Drop the numblio_id column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('numblio_id');
        });
    }
};
