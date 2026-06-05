@extends('layouts.auth')

@section('title', 'Crear cuenta | Biblia Inmobiliaria')

@section('content')
    <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
        <div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-700px p-lg-15 p-7 shadow-sm">
            <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">
                <form method="POST" action="{{ route('register') }}" class="form w-100" novalidate>
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger mb-8">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="text-center mb-11">
                        <h1 class="text-gray-900 fw-bolder mb-3">Crear cuenta</h1>
                        <div class="text-gray-500 fw-semibold fs-6">
                            Regístrate para administrar tu proyecto.
                        </div>
                    </div>

                    <div class="fv-row mb-8">
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="form-control form-control-lg bg-transparent @error('name') is-invalid @enderror"
                            placeholder="Nombre completo" required autofocus />
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="fv-row mb-8">
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control form-control-lg bg-transparent @error('email') is-invalid @enderror"
                            placeholder="Correo electrónico" required />
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="fv-row mb-8">
                        <input type="password" name="password"
                            class="form-control form-control-lg bg-transparent @error('password') is-invalid @enderror"
                            placeholder="Contraseña" required autocomplete="new-password" />
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="fv-row mb-10">
                        <input type="password" name="password_confirmation"
                            class="form-control form-control-lg bg-transparent @error('password_confirmation') is-invalid @enderror"
                            placeholder="Confirmar contraseña" required />
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mb-10">
                        <button type="submit" class="btn btn-primary btn-lg">Crear cuenta</button>
                    </div>

                    <div class="text-gray-500 text-center fw-semibold fs-6">
                        ¿Ya tienes cuenta?
                        <a href="{{ route('login') }}" class="link-primary">Inicia sesión</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
