<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public const ROLES = [
        'asesor',
        'administrador',
        'lider_equipo',
    ];

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'mobile_phone' => ['required', 'string', 'max:30'],
            'office_phone' => ['nullable', 'string', 'max:30'],
            'work_zone' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::in(self::ROLES)],
            'ampi_certificate' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $profilePhotoPath = $request->hasFile('profile_photo')
            ? $request->file('profile_photo')->store('profile-photos', 'public')
            : null;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_phone' => $request->mobile_phone,
            'office_phone' => $request->office_phone,
            'work_zone' => $request->work_zone,
            'company' => $request->company,
            'role' => $request->role,
            'ampi_certificate' => $request->ampi_certificate,
            'profile_photo_path' => $profilePhotoPath,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }
}
