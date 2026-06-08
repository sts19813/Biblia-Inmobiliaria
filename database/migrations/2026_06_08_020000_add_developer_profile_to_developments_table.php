<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            $table->foreignId('developer_profile_id')
                ->nullable()
                ->after('developer')
                ->constrained('developer_profiles')
                ->nullOnDelete();
            $table->string('developer')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('developer_profile_id');
            $table->string('developer')->nullable(false)->change();
        });
    }
};
