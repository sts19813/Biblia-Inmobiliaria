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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $paymentMethods = [
            'Contado',
            'Credito bancario',
            'Infonavit',
            'Cofinavit',
        ];

        DB::table('payment_methods')->insert(collect($paymentMethods)->map(fn (string $name) => [
            'name' => $name,
            'slug' => Str::slug($name),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ])->all());
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
