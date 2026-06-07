<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('map_url');
            $table->string('cover_image_path')->nullable()->after('logo_path');
            $table->longText('description')->nullable()->after('cover_image_path');
        });
    }

    public function down(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            $table->dropColumn([
                'logo_path',
                'cover_image_path',
                'description',
            ]);
        });
    }
};
