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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nomenklature', 32)->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->double('stock', 15, 2)->default(0);
            $table->enum('me', ['бр', 'кг', 'л', 'м']);
            $table->enum('type', [
                'Обща употреба',
                'Прасета угояване',
                'Фураж угояване',
                'Прасета ремонтни',
                'Фураж ремонтни',
                'Прасета заплождане',
                'Прасета условна бременност',
                'Прасета бременност',
                'Фураж бременни',
                'Прасета родилно',
                'Фураж кърмачки',
                'Прсета подрастване',
            ])->default('Обща употреба');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
