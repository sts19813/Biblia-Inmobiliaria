<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        Permission::findOrCreate('eliminar usuarios', 'web');
    }

    public function down(): void
    {
        Permission::query()
            ->where('name', 'eliminar usuarios')
            ->delete();
    }
};
