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
        Schema::create('table_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('row_id');
            $table->unsignedBigInteger('column_id');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->foreign('row_id')->references('id')->on('workspace_rows')->onDelete('cascade');
            $table->foreign('column_id')->references('id')->on('workspace_columns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_values');
    }
};
