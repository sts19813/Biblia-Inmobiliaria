<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public const ROLES = [
        'asesor' => 'Asesor',
        'administrador' => 'Administrador',
        'lider_equipo' => 'Lider de equipo',
    ];

    public function index()
    {
        return view('admin.users.index', [
            'users' => User::with('roles', 'permissions')->latest()->paginate(12),
            'roles' => Role::with('permissions')->orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
            'roleLabels' => self::ROLES,
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'user' => new User(['role' => 'asesor']),
            'roles' => Role::orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
            'roleLabels' => self::ROLES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $roleName = $data['role'];
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);

        $user = User::create($data);
        $user->syncRoles([$roleName]);
        $user->syncPermissions($permissions);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
            'roleLabels' => self::ROLES,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validatedData($request, $user);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $data['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $roleName = $data['role'];
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);

        $user->update($data);
        $user->syncRoles([$roleName]);
        $user->syncPermissions($permissions);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return back()->withErrors(['user' => 'No puedes eliminar tu propio usuario.']);
        }

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario eliminado correctamente.');
    }

    private function validatedData(Request $request, ?User $user = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user),
            ],
            'mobile_phone' => ['required', 'string', 'max:30'],
            'office_phone' => ['nullable', 'string', 'max:30'],
            'work_zone' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::exists('roles', 'name')],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')],
            'ampi_certificate' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::defaults()],
        ];

        $data = $request->validate($rules);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        unset($data['profile_photo']);

        return $data;
    }
}
