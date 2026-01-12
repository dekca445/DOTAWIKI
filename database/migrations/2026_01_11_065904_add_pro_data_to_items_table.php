<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // Cek dulu biar gak error kalau kolom stats/desc udah ada dari migrasi sebelumnya
            if (!Schema::hasColumn('items', 'desc')) {
                $table->text('desc')->nullable();
            }
            if (!Schema::hasColumn('items', 'stats')) {
                $table->json('stats')->nullable();
            }
            
            // Kolom BARU untuk Hero Rekomendasi
            $table->json('popular_heroes')->nullable(); 
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['desc', 'stats', 'popular_heroes']);
        });
    }
};