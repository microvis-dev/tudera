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
        Schema::create('workspace_columns', function (Blueprint $table) {
            $table->id('column_id');
            $table->foreignUuid('table_id')->constrained('workspace_tables')->onDelete('cascade');
            $table->enum('type', ['string', 'integer', 'float', 'datetime', 'status', 'ref'])->notNull();
            $table->string('column_name')->notNull();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_columns');
    }
};
