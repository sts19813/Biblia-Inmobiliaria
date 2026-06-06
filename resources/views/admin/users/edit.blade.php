@extends('layouts.admin')

@section('title', 'Editar usuario | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Editar usuario
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Master Brokers / Usuarios</li>
        </ul>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.users._form')
    </form>
@endsection
