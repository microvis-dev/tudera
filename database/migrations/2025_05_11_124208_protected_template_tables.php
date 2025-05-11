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
        // add protected column to workspace_tables
        Schema::table('workspace_tables', function (Blueprint $table) {
            $table->boolean('protected')->default(false)->after('name');
        });
        // enable nullable for workspace_tables.workspace_id
        Schema::table('workspace_tables', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // remove protected column from workspace_tables
        Schema::table('workspace_tables', function (Blueprint $table) {
            $table->dropColumn('protected');
        });
        // disable nullable for workspace_tables.workspace_id
        Schema::table('workspace_tables', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace_id')->nullable(false)->change();
        });
    }
};
