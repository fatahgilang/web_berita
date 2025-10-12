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
        Schema::table('banners', function (Blueprint $table) {
            // First, clean up any orphaned banners (banners that reference non-existent news)
            DB::statement('
                DELETE FROM banners 
                WHERE news_id NOT IN (SELECT id FROM news)
            ');
            
            // Add foreign key constraint with cascade delete
            $table->foreign('news_id')
                  ->references('id')
                  ->on('news')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['news_id']);
        });
    }
};
