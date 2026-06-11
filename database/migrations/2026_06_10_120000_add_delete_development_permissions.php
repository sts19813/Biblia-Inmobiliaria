<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        Permission::findOrCreate('eliminar desarrollo', 'web');
        Permission::findOrCreate('eliminar desarrolladora', 'web');
    }

    public function down(): void
    {
        Permission::query()
            ->whereIn('name', ['eliminar desarrollo', 'eliminar desarrolladora'])
            ->delete();
    }
};
