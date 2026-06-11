@extends('layouts.admin')

@section('title', 'Configuraciones | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Configuraciones
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Administracion</li>
        </ul>
    </div>
@endsection

@push('styles')
    <style>
        .settings-storage-card {
            overflow: hidden;
            border: 0;
            background: #111827;
            color: #fff;
        }

        .settings-storage-card .text-muted,
        .settings-upgrade-card .text-muted {
            color: rgba(255, 255, 255, .68) !important;
        }

        .settings-storage-meter {
            height: 14px;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(255, 255, 255, .16);
        }

        .settings-storage-meter-bar {
            height: 100%;
            border-radius: inherit;
            background: #28d17c;
        }

        .settings-stat {
            border-radius: 14px;
            background: rgba(255, 255, 255, .1);
            padding: 16px;
        }

        .settings-upgrade-card {
            min-height: 100%;
            border: 0;
            background: #0f766e;
            color: #fff;
        }

        .settings-upgrade-price {
            display: inline-flex;
            align-items: baseline;
            gap: 4px;
            padding: 10px 14px;
            border-radius: 14px;
            background: rgba(255, 255, 255, .14);
        }

        .settings-upgrade-list {
            display: grid;
            gap: 10px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .settings-upgrade-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, .86);
            font-weight: 600;
        }

        .settings-soft-panel {
            border: 1px solid rgba(15, 23, 42, .06);
            border-radius: 14px;
            background: #f8fafc;
            padding: 18px;
        }
    </style>
@endpush

@section('content')
    @php
        $storagePercentage = min(100, $miniDriveStorage['percentage']);
        $warningPercent = old('mini_drive_storage_warning_percent', $settings['mini_drive_storage_warning_percent']);
        $isNearLimit = $storagePercentage >= $warningPercent;
    @endphp

    <div class="row g-8 mb-8">
        <div class="col-xl-8">
            <div class="card card-flush settings-storage-card h-100">
                <div class="card-body p-8 p-lg-10">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-6 mb-10">
                        <div>
                            <div class="badge badge-light-success mb-4">MiniDrive</div>
                            <h2 class="fw-bold text-white mb-3">Almacenamiento documental</h2>
                            <div class="fs-6 text-muted fw-semibold">
                                {{ $miniDriveStorage['used_label'] }} ocupados de {{ $miniDriveStorage['limit_label'] }} contratados.
                            </div>
                        </div>
                        <div class="text-lg-end">
                            <div class="display-6 fw-bold text-white">{{ $miniDriveStorage['percentage_label'] }}%</div>
                            <div class="text-muted fw-semibold">ocupado</div>
                        </div>
                    </div>

                    <div class="settings-storage-meter mb-8" role="progressbar"
                        aria-valuenow="{{ round($storagePercentage, 2) }}"
                        aria-valuemin="0"
                        aria-valuemax="100">
                        <div class="settings-storage-meter-bar" style="width: {{ $storagePercentage }}%;"></div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="settings-stat">
                                <div class="text-muted fw-semibold mb-1">Ocupado exacto</div>
                                <div class="fw-bold fs-5 text-white">{{ $miniDriveStorage['used_exact_label'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-stat">
                                <div class="text-muted fw-semibold mb-1">Disponible</div>
                                <div class="fw-bold fs-5 text-white">{{ $miniDriveStorage['available_label'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="settings-stat">
                                <div class="text-muted fw-semibold mb-1">Tamano por archivo</div>
                                <div class="fw-bold fs-5 text-white">{{ $miniDriveUploadLimit['effective_label'] }}</div>
                            </div>
                        </div>
                    </div>

                    @if ($isNearLimit || $miniDriveStorage['is_over_limit'])
                        <div class="alert alert-warning mt-8 mb-0">
                            El MiniDrive esta cerca de su limite configurado. Considera liberar archivos o ampliar capacidad.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card card-flush settings-upgrade-card">
                <div class="card-body p-8 d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-7">
                        <div class="symbol symbol-55px">
                            <div class="symbol-label bg-white bg-opacity-15">
                                <i class="ki-outline ki-rocket fs-2x text-white"></i>
                            </div>
                        </div>
                        <span class="badge badge-light">Proximamente</span>
                    </div>
                    <h3 class="fw-bold text-white fs-2 mb-3">Escala tu MiniDrive</h3>
                    <p class="text-muted fw-semibold fs-6 mb-6">
                        Prepara paquetes extra de almacenamiento para desarrollos con mas planos, renders, ZIP y videos.
                    </p>
                    <div class="settings-upgrade-price mb-7">
                        <span class="fs-2 fw-bold text-white">+100 GB</span>
                        <span class="text-muted fw-semibold">plan sugerido</span>
                    </div>
                    <ul class="settings-upgrade-list mb-8">
                        <li><i class="ki-outline ki-check-circle fs-3 text-white"></i> Mas espacio documental</li>
                        <li><i class="ki-outline ki-check-circle fs-3 text-white"></i> Mejor margen para archivos ZIP</li>
                        <li><i class="ki-outline ki-check-circle fs-3 text-white"></i> Crecimiento sin limpiar carpetas</li>
                    </ul>
                    <button type="button" class="btn btn-light mt-auto" data-bs-toggle="modal" data-bs-target="#upgrade_plan_modal">
                        <i class="ki-outline ki-plus-square fs-2"></i>
                        Solicitar ampliacion
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PATCH')

        <div class="row g-8">
            <div class="col-xl-7">
                <div class="card card-flush h-100">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="fw-bold mb-0">Configuraciones generales</h3>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-6">
                            <div class="col-md-6">
                                <label for="system_name" class="form-label fw-semibold">Nombre del sistema</label>
                                <input id="system_name" type="text" name="system_name" class="form-control form-control-solid"
                                    value="{{ old('system_name', $settings['system_name']) }}" maxlength="120" required>
                            </div>
                            <div class="col-md-6">
                                <label for="support_email" class="form-label fw-semibold">Email de soporte</label>
                                <input id="support_email" type="email" name="support_email" class="form-control form-control-solid"
                                    value="{{ old('support_email', $settings['support_email']) }}" maxlength="150">
                            </div>
                            <div class="col-md-6">
                                <label for="mini_drive_storage_warning_percent" class="form-label fw-semibold">Alerta de almacenamiento</label>
                                <div class="input-group input-group-solid">
                                    <input id="mini_drive_storage_warning_percent" type="number" name="mini_drive_storage_warning_percent"
                                        class="form-control form-control-solid" min="50" max="100"
                                        value="{{ old('mini_drive_storage_warning_percent', $settings['mini_drive_storage_warning_percent']) }}" required>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="mini_drive_default_visibility" class="form-label fw-semibold">Visibilidad de nuevas cargas</label>
                                <select id="mini_drive_default_visibility" name="mini_drive_default_visibility" class="form-select form-select-solid" required>
                                    <option value="public" @selected(old('mini_drive_default_visibility', $settings['mini_drive_default_visibility']) === 'public')>Publico</option>
                                    <option value="private" @selected(old('mini_drive_default_visibility', $settings['mini_drive_default_visibility']) === 'private')>Privado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="card card-flush h-100">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="fw-bold mb-0">MiniDrive</h3>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-6">
                            <div class="col-md-6">
                                <label for="mini_drive_storage_limit_gb" class="form-label fw-semibold">Capacidad contratada</label>
                                <div class="input-group input-group-solid">
                                    <input id="mini_drive_storage_limit_gb" type="number" step="0.5" name="mini_drive_storage_limit_gb"
                                        class="form-control form-control-solid" min="1" max="10240"
                                        value="{{ old('mini_drive_storage_limit_gb', $settings['mini_drive_storage_limit_gb']) }}" required>
                                    <span class="input-group-text">GB</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="mini_drive_max_file_size_mb" class="form-label fw-semibold">Maximo por archivo</label>
                                <div class="input-group input-group-solid">
                                    <input id="mini_drive_max_file_size_mb" type="number" name="mini_drive_max_file_size_mb"
                                        class="form-control form-control-solid" min="1" max="2048"
                                        value="{{ old('mini_drive_max_file_size_mb', $settings['mini_drive_max_file_size_mb']) }}" required>
                                    <span class="input-group-text">MB</span>
                                </div>
                            </div>
                        </div>

                        <div class="settings-soft-panel mt-7">
                            <div class="d-flex align-items-start gap-4">
                                <i class="ki-outline ki-information-5 fs-2x text-primary"></i>
                                <div>
                                    <div class="fw-bold text-gray-900 mb-1">Limite efectivo de carga</div>
                                    <div class="text-muted fw-semibold">
                                        La configuracion actual permite {{ $miniDriveUploadLimit['configured_label'] }} por archivo.
                                        El servidor acepta {{ $miniDriveUploadLimit['server_label'] }}.
                                        @if ($miniDriveUploadLimit['is_server_limited'])
                                            El limite real en este entorno es {{ $miniDriveUploadLimit['effective_label'] }}.
                                        @else
                                            El limite real coincide con la configuracion.
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-8">
                            <button type="submit" class="btn btn-primary">
                                <i class="ki-outline ki-check fs-2"></i>
                                Guardar configuraciones
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="upgrade_plan_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Ampliacion de almacenamiento</h3>
                    <button type="button" class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal" aria-label="Cerrar">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-6">
                        <i class="ki-outline ki-lock-2 fs-4x text-primary mb-5"></i>
                        <h4 class="fw-bold text-gray-900 mb-3">Compra en preparacion</h4>
                        <p class="text-muted fw-semibold mb-0">
                            Este flujo aun no procesa pagos. La tarjeta queda lista como punto de entrada para planes de mas GB.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    @if ($settings['support_email'])
                        <a href="mailto:{{ $settings['support_email'] }}?subject=Ampliacion%20MiniDrive" class="btn btn-primary">
                            Contactar soporte
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
