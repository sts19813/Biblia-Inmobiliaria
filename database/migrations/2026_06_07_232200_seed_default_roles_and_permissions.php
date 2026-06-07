<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            'gestionar usuarios',
            'gestionar roles',
            'gestionar desarrollos',
            'ver documentos',
            'subir documentos',
            'eliminar documentos',
            'gestionar permisos documentos',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $admin = Role::findOrCreate('administrador', 'web');
        $leader = Role::findOrCreate('lider_equipo', 'web');
        $advisor = Role::findOrCreate('asesor', 'web');

        $admin->syncPermissions($permissions);
        $leader->syncPermissions([
            'gestionar desarrollos',
            'ver documentos',
            'subir documentos',
        ]);
        $advisor->syncPermissions([
            'ver documentos',
            'subir documentos',
        ]);

        User::query()->each(function (User $user) {
            if ($user->role) {
                $user->syncRoles([$user->role]);
            }
        });
    }

    public function down(): void
    {
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('model_has_permissions')->delete();

        Role::query()->whereIn('name', ['administrador', 'lider_equipo', 'asesor'])->delete();
        Permission::query()->whereIn('name', [
            'gestionar usuarios',
            'gestionar roles',
            'gestionar desarrollos',
            'ver documentos',
            'subir documentos',
            'eliminar documentos',
            'gestionar permisos documentos',
        ])->delete();
    }
};
