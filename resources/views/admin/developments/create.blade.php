@extends('layouts.admin')

@section('title', 'Nuevo desarrollo | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Nuevo desarrollo
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Desarrollos</li>
        </ul>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.developments.store') }}" data-development-form>
        @csrf
        @include('admin.developments._form')
    </form>
@endsection
