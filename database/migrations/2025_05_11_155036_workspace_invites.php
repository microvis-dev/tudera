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
        Schema::create('workspace_invites', function (Blueprint $table) {
            $table->uuid('invite_id')->unique()->primary();
            $table->bigInteger('workspace_id')->unsigned();
            $table->boolean('used')->default(false);
            $table->timestamps();
        });

        Schema::table('workspace_invites', function (Blueprint $table) {
            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_invites');
    }
};
