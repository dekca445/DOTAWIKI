<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Hapus tabel lama jika ada (Biar bersih)
        Schema::dropIfExists('items');

        // 2. Buat tabel baru khusus JSON structure
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // ID Internal (blink, travel_boots)
            $table->string('dname')->nullable(); // Nama Asli (Blink Dagger)
            $table->integer('cost')->default(0);
            
            $table->string('img_url')->nullable();
            
            // Kolom Data Lengkap
            $table->text('desc')->nullable();        // Gabungan Hint + CD + Mana
            $table->text('lore')->nullable();        // Lore item (Background story)
            $table->json('stats')->nullable();       // Atribut (+Damage, +Str)
            $table->json('components')->nullable();  // Resep (Array nama item)
            
            $table->boolean('recipe_cost')->default(0); // Penanda apakah ini kertas resep
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};