<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeveloperProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeveloperProfileController extends Controller
{
    public function index(Request $request)
    {
        $profiles = DeveloperProfile::query()
            ->latest()
            ->paginate(15);

        return view('admin.developer-profile.index', compact('profiles'));
    }

    public function create()
    {
        return view('admin.developer-profile.create', [
            'profile' => new DeveloperProfile(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['created_by'] = $request->user()?->id;

        $profile = DeveloperProfile::create($this->storeImages($request, $data));

        return $this->savedResponse($request, $profile, 'Desarrolladora creada correctamente.');
    }

    public function show(DeveloperProfile $developerProfile)
    {
        return view('admin.developer-profile.show', [
            'profile' => $developerProfile,
        ]);
    }

    public function edit(DeveloperProfile $developerProfile)
    {
        return view('admin.developer-profile.edit', [
            'profile' => $developerProfile,
        ]);
    }

    public function update(Request $request, DeveloperProfile $developerProfile)
    {
        $data = $this->validatedData($request);
        $data = $this->storeImages($request, $data, $developerProfile);

        $developerProfile->update($data);

        return $this->savedResponse($request, $developerProfile, 'Desarrolladora actualizada correctamente.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'commercial_name' => ['required', 'string', 'max:180'],
            'legal_name' => ['required', 'string', 'max:220'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
            'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:8192'],
            'website' => ['nullable', 'url', 'max:220'],
            'corporate_email' => ['nullable', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:60'],
            'whatsapp' => ['nullable', 'string', 'max:60'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'country' => ['nullable', 'string', 'max:120'],
            'short_description' => ['nullable', 'string', 'max:150'],
            'long_description' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'url', 'max:220'],
            'instagram_url' => ['nullable', 'url', 'max:220'],
            'linkedin_url' => ['nullable', 'url', 'max:220'],
            'twitter_url' => ['nullable', 'url', 'max:220'],
        ], [
            'commercial_name.required' => 'El nombre comercial es obligatorio.',
            'legal_name.required' => 'La razon social es obligatoria.',
            '*.url' => 'Captura una URL valida.',
            'corporate_email.email' => 'Captura un email corporativo valido.',
            'logo.mimes' => 'El logo debe ser JPG, PNG, WEBP o SVG.',
            'cover_image.mimes' => 'La portada debe ser JPG, PNG, WEBP o SVG.',
        ]);
    }

    private function storeImages(Request $request, array $data, ?DeveloperProfile $profile = null): array
    {
        unset($data['logo'], $data['cover_image']);

        if ($request->hasFile('logo')) {
            if ($profile?->logo_path) {
                Storage::disk('public')->delete($profile->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('developer-profiles/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($profile?->cover_image_path) {
                Storage::disk('public')->delete($profile->cover_image_path);
            }

            $data['cover_image_path'] = $request->file('cover_image')->store('developer-profiles/covers', 'public');
        }

        return $data;
    }

    private function savedResponse(Request $request, DeveloperProfile $profile, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'redirect' => route('admin.developer-profile.show', $profile),
            ]);
        }

        return redirect()
            ->route('admin.developer-profile.show', $profile)
            ->with('status', $message);
    }
}
