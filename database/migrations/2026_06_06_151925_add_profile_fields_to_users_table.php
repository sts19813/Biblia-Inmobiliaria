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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile_phone')->nullable()->after('password');
            $table->string('office_phone')->nullable()->after('mobile_phone');
            $table->string('work_zone')->nullable()->after('office_phone');
            $table->string('company')->nullable()->after('work_zone');
            $table->enum('role', ['asesor', 'administrador', 'lider_equipo'])->default('asesor')->after('company');
            $table->string('ampi_certificate')->nullable()->after('role');
            $table->string('profile_photo_path')->nullable()->after('ampi_certificate');
            $table->string('google_id')->nullable()->unique()->after('profile_photo_path');
            $table->string('google_avatar_url')->nullable()->after('google_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['google_id']);
            $table->dropColumn([
                'mobile_phone',
                'office_phone',
                'work_zone',
                'company',
                'role',
                'ampi_certificate',
                'profile_photo_path',
                'google_id',
                'google_avatar_url',
            ]);
        });
    }
};
