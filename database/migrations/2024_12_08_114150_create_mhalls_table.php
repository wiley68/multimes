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
        Schema::create('mhalls', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('factory_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('silo_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->enum('type', [
                'Ремонтни',
                'Заплождане',
                'Условна бременност',
                'Бременност',
                'Родилно',
                'Подрастване',
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mhalls');
    }
};
