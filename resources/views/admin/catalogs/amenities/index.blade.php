@extends('layouts.admin')

@section('title', 'Catalogo de amenidades | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Catalogos
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Amenidades</li>
        </ul>
    </div>
    <button type="button" class="btn btn-primary" data-amenity-create>
        <i class="ki-outline ki-plus fs-2"></i>
        Nueva amenidad
    </button>
@endsection

@section('content')
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div>
                    <h3 class="fw-bold text-gray-900 mb-1">Catalogo de amenidades</h3>
                    <div class="text-muted fw-semibold fs-7">Administra las opciones que apareceran en el formulario de desarrollos.</div>
                </div>
            </div>
            <div class="card-toolbar">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                    <input type="text" class="form-control form-control-solid w-250px ps-12" placeholder="Buscar..."
                        data-amenity-search>
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" data-amenity-table>
                    <thead>
                        <tr class="text-start text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                            <th>Amenidad</th>
                            <th>Icono</th>
                            <th>Estado</th>
                            <th>Actualizacion</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($amenities as $amenity)
                            <tr data-amenity-row="{{ $amenity->id }}">
                                <td>
                                    <div class="fw-bold text-gray-900" data-amenity-row-name>{{ $amenity->name }}</div>
                                    <div class="text-muted fs-7">{{ $amenity->slug }}</div>
                                </td>
                                <td>
                                    @if ($amenity->icon)
                                        <span class="badge badge-light">{{ $amenity->icon }}</span>
                                    @else
                                        <span class="text-muted">Sin icono</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $amenity->is_active ? 'badge-light-success' : 'badge-light' }}" data-amenity-row-status>
                                        {{ $amenity->is_active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </td>
                                <td>{{ $amenity->updated_at?->diffForHumans() }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-icon btn-light btn-active-light-primary btn-sm"
                                        data-amenity-edit
                                        data-action="{{ route('admin.catalogs.amenities.update', $amenity) }}"
                                        data-name="{{ $amenity->name }}"
                                        data-icon="{{ $amenity->icon }}"
                                        data-active="{{ $amenity->is_active ? '1' : '0' }}"
                                        title="Editar">
                                        <i class="ki-outline ki-pencil fs-2"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.catalogs.amenities.destroy', $amenity) }}"
                                        class="d-inline" data-amenity-delete>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-light btn-active-light-danger btn-sm" title="Eliminar">
                                            <i class="ki-outline ki-trash fs-2"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="amenity_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.catalogs.amenities.store') }}" data-amenity-form>
                    @csrf
                    <input type="hidden" name="_method" value="POST" data-amenity-method>
                    <div class="modal-header">
                        <h3 class="modal-title" data-amenity-modal-title>Nueva amenidad</h3>
                        <button type="button" class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal" aria-label="Cerrar">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-6">
                            <label class="form-label required">Nombre</label>
                            <input type="text" name="name" class="form-control form-control-solid" required maxlength="120"
                                placeholder="Alberca">
                        </div>
                        <div class="mb-6">
                            <label class="form-label">Icono Metronic</label>
                            <input type="text" name="icon" class="form-control form-control-solid" maxlength="80"
                                placeholder="ki-outline ki-star">
                            <div class="form-text">Opcional. Puedes usar clases de KeenIcons si despues quieres mostrar iconos.</div>
                        </div>
                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                            <span class="form-check-label fw-semibold text-gray-700">Amenidad activa</span>
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" data-amenity-submit>
                            <span class="indicator-label">Guardar</span>
                            <span class="indicator-progress">
                                Guardando...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalElement = document.getElementById('amenity_modal');
            var modal = window.bootstrap ? bootstrap.Modal.getOrCreateInstance(modalElement) : null;
            var form = document.querySelector('[data-amenity-form]');
            var methodInput = document.querySelector('[data-amenity-method]');
            var title = document.querySelector('[data-amenity-modal-title]');
            var submit = document.querySelector('[data-amenity-submit]');
            var search = document.querySelector('[data-amenity-search]');
            var table = document.querySelector('[data-amenity-table]');

            function toast(icon, text) {
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: icon,
                        title: text,
                        showConfirmButton: false,
                        timer: 3600,
                        timerProgressBar: true
                    });
                    return;
                }

                window.alert(text);
            }

            function firstError(response) {
                if (!response || !response.errors) {
                    return response && response.message ? response.message : 'No se pudo guardar.';
                }

                for (var key in response.errors) {
                    if (Object.prototype.hasOwnProperty.call(response.errors, key) && response.errors[key].length) {
                        return response.errors[key][0];
                    }
                }

                return response.message || 'No se pudo guardar.';
            }

            document.querySelector('[data-amenity-create]')?.addEventListener('click', function () {
                form.reset();
                form.action = '{{ route('admin.catalogs.amenities.store') }}';
                methodInput.value = 'POST';
                title.textContent = 'Nueva amenidad';
                form.querySelector('[name="is_active"]').checked = true;
                modal?.show();
            });

            document.querySelectorAll('[data-amenity-edit]').forEach(function (button) {
                button.addEventListener('click', function () {
                    form.reset();
                    form.action = button.dataset.action;
                    methodInput.value = 'PATCH';
                    title.textContent = 'Editar amenidad';
                    form.querySelector('[name="name"]').value = button.dataset.name || '';
                    form.querySelector('[name="icon"]').value = button.dataset.icon || '';
                    form.querySelector('[name="is_active"]').checked = button.dataset.active === '1';
                    modal?.show();
                });
            });

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                submit.disabled = true;
                submit.setAttribute('data-kt-indicator', 'on');

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: new FormData(form)
                })
                    .then(function (response) {
                        return response.json().then(function (payload) {
                            if (!response.ok) {
                                throw payload;
                            }

                            return payload;
                        });
                    })
                    .then(function (payload) {
                        toast('success', payload.message || 'Amenidad guardada correctamente.');
                        window.setTimeout(function () {
                            window.location.reload();
                        }, 700);
                    })
                    .catch(function (error) {
                        toast('error', firstError(error));
                    })
                    .finally(function () {
                        submit.disabled = false;
                        submit.removeAttribute('data-kt-indicator');
                    });
            });

            document.querySelectorAll('[data-amenity-delete]').forEach(function (deleteForm) {
                deleteForm.addEventListener('submit', function (event) {
                    event.preventDefault();

                    var confirmed = window.confirm('Seguro que deseas eliminar esta amenidad?');

                    if (!confirmed) {
                        return;
                    }

                    fetch(deleteForm.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: new FormData(deleteForm)
                    })
                        .then(function (response) {
                            if (!response.ok) {
                                throw new Error('No se pudo eliminar.');
                            }

                            return response.json();
                        })
                        .then(function (payload) {
                            toast('success', payload.message || 'Amenidad eliminada correctamente.');
                            deleteForm.closest('tr')?.remove();
                        })
                        .catch(function () {
                            toast('error', 'No se pudo eliminar la amenidad.');
                        });
                });
            });

            search?.addEventListener('input', function () {
                var needle = search.value.toLowerCase();

                table?.querySelectorAll('tbody tr').forEach(function (row) {
                    row.style.display = row.textContent.toLowerCase().includes(needle) ? '' : 'none';
                });
            });
        });
    </script>
@endpush
