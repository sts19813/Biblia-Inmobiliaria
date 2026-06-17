<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function index()
    {
        return view('admin.catalogs.payment-methods.index', [
            'paymentMethods' => PaymentMethod::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $paymentMethod = PaymentMethod::create($this->validatedData($request));

        return $this->response($request, 'Forma de pago creada correctamente.', $paymentMethod);
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->update($this->validatedData($request, $paymentMethod));

        return $this->response($request, 'Forma de pago actualizada correctamente.', $paymentMethod);
    }

    public function destroy(Request $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Forma de pago eliminada correctamente.',
            ]);
        }

        return back()->with('status', 'Forma de pago eliminada correctamente.');
    }

    private function validatedData(Request $request, ?PaymentMethod $paymentMethod = null): array
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('payment_methods', 'name')->ignore($paymentMethod?->id),
            ],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'El nombre de la forma de pago es obligatorio.',
            'name.unique' => 'Ya existe una forma de pago con ese nombre.',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        return $data;
    }

    private function response(Request $request, string $message, PaymentMethod $paymentMethod)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'payment_method' => $paymentMethod,
            ]);
        }

        return back()->with('status', $message);
    }
}
