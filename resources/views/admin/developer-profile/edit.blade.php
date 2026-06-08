@extends('layouts.admin')

@section('title', 'Editar desarrolladora | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <a href="{{ route('admin.developer-profile.show', $profile) }}" class="text-muted text-hover-primary fw-semibold d-inline-flex align-items-center mb-3">
            <i class="ki-outline ki-arrow-left fs-3 me-1"></i>
            Volver al perfil
        </a>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Editar desarrolladora
        </h1>
        <div class="text-muted fw-semibold fs-6 mt-1">{{ $profile->commercial_name }}</div>
    </div>
    <button type="submit" form="developer_profile_form" class="btn btn-primary" data-developer-profile-submit>
        <span class="indicator-label">
            <i class="ki-outline ki-save-2 fs-2"></i>
            Guardar cambios
        </span>
        <span class="indicator-progress">
            Guardando...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
    </button>
@endsection

@section('content')
    @include('admin.developer-profile._form')
@endsection
