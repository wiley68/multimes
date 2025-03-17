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
        Schema::create('uproductions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('status')->default(1);
            $table->foreignId('uhall_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->timestamp('finished_at')->nullable();
            $table->double('stock', 15, 2)->default(0);
            $table->decimal('price', 8, 2)->default(0);
            $table->unsignedBigInteger('product_id')->default(0);
            $table->unsignedInteger('group_number');
            $table->unsignedInteger('partida_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uproductions');
    }
};
