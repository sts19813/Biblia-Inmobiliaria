<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('developer_profiles', function (Blueprint $table) {
            $table->string('legal_name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('developer_profiles', function (Blueprint $table) {
            $table->string('legal_name')->nullable(false)->change();
        });
    }
};
