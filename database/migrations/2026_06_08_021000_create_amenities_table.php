<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $amenities = [
            'Alberca',
            'Gimnasio',
            'Caseta de vigilancia',
            'Seguridad 24/7',
            'Casa club',
            'Area social',
            'Salon de eventos',
            'Terraza',
            'Rooftop',
            'Juegos infantiles',
            'Parque central',
            'Areas verdes',
            'Cancha de padel',
            'Cancha deportiva',
            'Pet park',
            'Asadores',
            'Coworking',
            'Ludoteca',
            'Elevador',
            'Estacionamiento techado',
            'Bodega',
            'Acceso controlado',
        ];

        DB::table('amenities')->insert(collect($amenities)->map(fn (string $name) => [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ])->all());
    }

    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
