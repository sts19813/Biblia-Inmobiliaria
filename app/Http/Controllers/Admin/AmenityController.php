<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AmenityController extends Controller
{
    public function index()
    {
        return view('admin.catalogs.amenities.index', [
            'amenities' => Amenity::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $amenity = Amenity::create($this->validatedData($request));

        return $this->response($request, 'Amenidad creada correctamente.', $amenity);
    }

    public function update(Request $request, Amenity $amenity)
    {
        $amenity->update($this->validatedData($request, $amenity));

        return $this->response($request, 'Amenidad actualizada correctamente.', $amenity);
    }

    public function destroy(Request $request, Amenity $amenity)
    {
        $amenity->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Amenidad eliminada correctamente.',
            ]);
        }

        return back()->with('status', 'Amenidad eliminada correctamente.');
    }

    private function validatedData(Request $request, ?Amenity $amenity = null): array
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('amenities', 'name')->ignore($amenity?->id),
            ],
            'icon' => ['nullable', 'string', 'max:80'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'El nombre de la amenidad es obligatorio.',
            'name.unique' => 'Ya existe una amenidad con ese nombre.',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        return $data;
    }

    private function response(Request $request, string $message, Amenity $amenity)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'amenity' => $amenity,
            ]);
        }

        return back()->with('status', $message);
    }
}
