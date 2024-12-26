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
        Schema::create('silos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('maxqt', 15, 2);
            $table->double('stock', 15, 2)->default(0);
            $table->decimal('price', 8, 2)->default(0);
            $table->unsignedBigInteger('product_id')->default(0);
            $table->foreignId('factory_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('silos');
    }
};
