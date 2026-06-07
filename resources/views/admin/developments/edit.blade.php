@extends('layouts.admin')

@section('title', 'Editar desarrollo | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Editar desarrollo
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('admin.developments.index') }}" class="text-muted text-hover-primary">Desarrollos</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">{{ $development->name }}</li>
        </ul>
    </div>
    <a href="{{ route('admin.developments.show', $development) }}" class="btn btn-light-primary">
        <i class="ki-outline ki-eye fs-2"></i>
        Visualizar
    </a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.developments.update', $development) }}" enctype="multipart/form-data" data-development-form>
        @csrf
        @method('PATCH')
        @include('admin.developments._form')
    </form>
@endsection
