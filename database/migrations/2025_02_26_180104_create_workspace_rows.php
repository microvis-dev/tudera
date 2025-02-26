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
        Schema::create('workspace_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('column_id');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->foreign('column_id')->references('column_id')->on('workspace_columns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_rows');
    }
};
