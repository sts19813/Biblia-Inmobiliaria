<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('developments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('developer');
            $table->string('property_type', 40);
            $table->string('city');
            $table->string('zone');
            $table->string('map_url', 700);
            $table->decimal('price_from', 15, 2);
            $table->decimal('price_per_m2', 15, 2);
            $table->decimal('down_payment', 15, 2);
            $table->decimal('monthly_payments', 15, 2);
            $table->text('payment_methods');
            $table->date('delivery_date');
            $table->string('status', 40);
            $table->json('amenities');
            $table->decimal('commission_percentage', 5, 2);
            $table->decimal('advisor_bonus', 15, 2)->nullable();
            $table->text('active_promotions')->nullable();
            $table->string('availability');
            $table->unsignedInteger('total_units')->nullable();
            $table->decimal('maintenance_fee', 15, 2)->nullable();
            $table->json('property_details')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['property_type', 'status']);
            $table->index(['city', 'zone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developments');
    }
};
