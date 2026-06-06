@php
    $isEditing = $user->exists;
@endphp

<div class="row g-8">
    <div class="col-xl-4">
        <div class="card card-flush">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="fw-bold mb-0">Foto de perfil</h3>
                </div>
            </div>
            <div class="card-body text-center">
                <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-4">
                    <div class="image-input-wrapper w-150px h-150px"
                        style="background-image: url('{{ $user->avatarUrl() }}'); background-size: cover; background-position: center;">
                    </div>
                </div>

                <input type="file" name="profile_photo"
                    class="form-control form-control-solid @error('profile_photo') is-invalid @enderror"
                    accept="image/*">
                @error('profile_photo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                @if ($user->google_avatar_url)
                    <div class="badge badge-light-info mt-5">Foto vinculada a Google</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card card-flush">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="fw-bold mb-0">Datos del usuario</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-6">
                    <div class="col-md-6">
                        <label class="required form-label">Nombre completo</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="form-control form-control-solid @error('name') is-invalid @enderror"
                            placeholder="Nombre y apellidos del asesor" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="required form-label">Correo electronico</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="form-control form-control-solid @error('email') is-invalid @enderror"
                            placeholder="Email de acceso y contacto" required>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="required form-label">Celular (WhatsApp)</label>
                        <input type="tel" name="mobile_phone" value="{{ old('mobile_phone', $user->mobile_phone) }}"
                            class="form-control form-control-solid @error('mobile_phone') is-invalid @enderror"
                            placeholder="Numero de contacto" required>
                        @error('mobile_phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Telefono de oficina</label>
                        <input type="tel" name="office_phone" value="{{ old('office_phone', $user->office_phone) }}"
                            class="form-control form-control-solid @error('office_phone') is-invalid @enderror"
                            placeholder="Numero de contacto">
                        @error('office_phone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="required form-label">Ciudad / zona de trabajo</label>
                        <input type="text" name="work_zone" value="{{ old('work_zone', $user->work_zone) }}"
                            class="form-control form-control-solid @error('work_zone') is-invalid @enderror"
                            placeholder="Donde opera principalmente" required>
                        @error('work_zone')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Inmobiliaria / empresa</label>
                        <input type="text" name="company" value="{{ old('company', $user->company) }}"
                            class="form-control form-control-solid @error('company') is-invalid @enderror"
                            placeholder="Agencia o independiente">
                        @error('company')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="required form-label">Rol</label>
                        <select name="role" class="form-select form-select-solid @error('role') is-invalid @enderror"
                            data-control="select2" data-hide-search="true" required>
                            @foreach ($roles as $value => $label)
                                <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Certificado AMPI</label>
                        <input type="text" name="ampi_certificate"
                            value="{{ old('ampi_certificate', $user->ampi_certificate) }}"
                            class="form-control form-control-solid @error('ampi_certificate') is-invalid @enderror"
                            placeholder="Folio, clave o estatus">
                        @error('ampi_certificate')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="{{ $isEditing ? '' : 'required' }} form-label">Contrasena</label>
                        <input type="password" name="password"
                            class="form-control form-control-solid @error('password') is-invalid @enderror"
                            autocomplete="new-password" {{ $isEditing ? '' : 'required' }}>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="{{ $isEditing ? '' : 'required' }} form-label">Confirmar contrasena</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-solid"
                            autocomplete="new-password" {{ $isEditing ? '' : 'required' }}>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ki-outline ki-check fs-2"></i>
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>
