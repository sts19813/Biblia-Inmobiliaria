@extends('layouts.admin')

@section('title', 'Dashboard | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Dashboard
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Administracion</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="row g-5 g-xl-10">
        <div class="col-md-4">
            <div class="card card-flush h-md-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-gray-500 fw-semibold fs-6">Usuarios registrados</span>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $usersCount }}</div>
                    </div>
                    <i class="ki-outline ki-people fs-3x text-primary"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-flush h-md-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-gray-500 fw-semibold fs-6">Master brokers / asesores</span>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $masterBrokersCount }}</div>
                    </div>
                    <i class="ki-outline ki-user-square fs-3x text-info"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-flush h-md-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-gray-500 fw-semibold fs-6">Administradores</span>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $developersCount }}</div>
                    </div>
                    <i class="ki-outline ki-bank fs-3x text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-flush mt-10">
        <div class="card-header">
            <div class="card-title">
                <h3 class="fw-bold mb-0">Operacion comercial</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
                <div class="d-flex flex-stack flex-grow-1">
                    <div class="fw-semibold">
                        <div class="fs-6 text-gray-700">Sin actividad comercial registrada por el momento.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
