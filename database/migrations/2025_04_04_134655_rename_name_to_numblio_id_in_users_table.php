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
        // Step 1: Add username, first_name, last_name fields
        Schema::table('users', function (Blueprint $table) {
            // First add username, first_name, last_name
            $table->string('username', 255)->unique()->after('name')->nullable();
            $table->string('first_name')->after('username')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
        });
        
        // Step 2: Copy data from name to first_name and last_name
        DB::statement("UPDATE users SET 
            username = SUBSTRING(LOWER(CONCAT(name, id)), 1, 255),
            first_name = SUBSTRING_INDEX(name, ' ', 1),
            last_name = CASE 
                WHEN CHAR_LENGTH(name) - CHAR_LENGTH(REPLACE(name, ' ', '')) > 0 
                THEN SUBSTRING(name, LOCATE(' ', name) + 1)
                ELSE ''
            END"
        );
        
        // Ensure all users have a valid username before making it non-nullable
        DB::statement("UPDATE users SET username = CONCAT('user', id) WHERE username IS NULL OR username = ''");
        
        // Step 3: Make fields non-nullable
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->change();
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
        });
        
        // Step 4: Add the numblio_id field
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('numblio_id')->after('id')->nullable();
        });
        
        // Step 5: Update with UUIDs
        DB::statement('UPDATE users SET numblio_id = UUID()');
        
        // Step 6: Make numblio_id required and unique
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('numblio_id')->nullable(false)->unique()->change();
        });
        
        // Step 7: Drop the name column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Add back the name column if it doesn't exist
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->after('id')->nullable();
            }
        });

        // Step 2: Populate name from first_name and last_name, if they exist
        if (Schema::hasColumn('users', 'first_name') && Schema::hasColumn('users', 'last_name')) {
            DB::statement("UPDATE users SET name = CONCAT(first_name, ' ', last_name)");

            // Step 3: Make name required only if we could populate it and it exists
            Schema::table('users', function (Blueprint $table) {
                 if (Schema::hasColumn('users', 'name')) {
                    $table->string('name')->nullable(false)->change();
                 }
            });
        } else {
             // Ensure name is nullable if population wasn't possible and it exists
              Schema::table('users', function (Blueprint $table) {
                 if (Schema::hasColumn('users', 'name')) {
                     $table->string('name')->nullable()->change();
                 }
             });
        }

        // Step 4: Drop the fields we added (if they exist)
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('users', 'numblio_id')) $columnsToDrop[] = 'numblio_id';
            if (Schema::hasColumn('users', 'username')) $columnsToDrop[] = 'username';
            if (Schema::hasColumn('users', 'first_name')) $columnsToDrop[] = 'first_name';
            if (Schema::hasColumn('users', 'last_name')) $columnsToDrop[] = 'last_name';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
