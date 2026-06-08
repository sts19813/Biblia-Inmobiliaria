@php
    $isEdit = $profile->exists;
@endphp

@push('styles')
    <style>
        .developer-dropzone {
            border: 2px dashed var(--bs-gray-300);
            border-radius: .75rem;
            min-height: 150px;
            cursor: pointer;
            transition: border-color .2s ease, background-color .2s ease;
        }

        .developer-dropzone:hover,
        .developer-dropzone.is-dragging {
            border-color: var(--bs-primary);
            background-color: var(--bs-primary-light);
        }

        .developer-preview-cover {
            height: 118px;
            border-radius: .75rem .75rem 0 0;
            background: linear-gradient(135deg, #4f46e5, #a855f7);
            background-size: cover;
            background-position: center;
        }

        .developer-preview-logo {
            width: 82px;
            height: 82px;
            object-fit: contain;
            margin-top: -41px;
            border: 8px solid var(--bs-body-bg);
        }

        .developer-preview-placeholder {
            width: 82px;
            height: 82px;
            margin-top: -41px;
            border: 8px solid var(--bs-body-bg);
        }
    </style>
@endpush

<form id="developer_profile_form"
    method="POST"
    action="{{ $isEdit ? route('admin.developer-profile.update', $profile) : route('admin.developer-profile.store') }}"
    enctype="multipart/form-data"
    data-developer-profile-form>
    @csrf
    @if ($isEdit)
        @method('PATCH')
    @endif

    <div class="row g-8">
        <div class="col-xl-8">
            <div class="card card-flush mb-8">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Informacion general</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-6">
                        <label class="form-label fw-semibold required">Nombre comercial</label>
                        <input type="text" name="commercial_name" class="form-control form-control-solid"
                            value="{{ old('commercial_name', $profile->commercial_name) }}"
                            placeholder="Desarrollos Premium S.A. de C.V."
                            data-preview-text="commercial_name" required>
                    </div>
                    <div class="mb-6">
                        <label class="form-label fw-semibold required">Razon social</label>
                        <input type="text" name="legal_name" class="form-control form-control-solid"
                            value="{{ old('legal_name', $profile->legal_name) }}"
                            placeholder="Desarrollos Premium Sociedad Anonima de Capital Variable" required>
                    </div>

                    <div class="row g-6">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Logo de la empresa</label>
                            <label class="developer-dropzone d-flex align-items-center justify-content-center text-center p-6"
                                data-file-dropzone for="developer_logo_input">
                                <span>
                                    <i class="ki-outline ki-file-up fs-2x text-gray-500 d-block mb-3"></i>
                                    <span class="fw-semibold text-gray-700 d-block">Subir logo</span>
                                    <span class="text-muted fs-8 d-block mt-2">JPG, PNG, WEBP o SVG</span>
                                </span>
                            </label>
                            <input id="developer_logo_input" type="file" name="logo" class="d-none"
                                accept="image/png,image/jpeg,image/webp,image/svg+xml,.svg"
                                data-image-input data-image-target="logo">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Imagen de portada</label>
                            <label class="developer-dropzone d-flex align-items-center justify-content-center text-center p-6"
                                data-file-dropzone for="developer_cover_input">
                                <span>
                                    <i class="ki-outline ki-file-up fs-2x text-gray-500 d-block mb-3"></i>
                                    <span class="fw-semibold text-gray-700 d-block">Subir portada</span>
                                    <span class="text-muted fs-8 d-block mt-2">JPG, PNG, WEBP o SVG</span>
                                </span>
                            </label>
                            <input id="developer_cover_input" type="file" name="cover_image" class="d-none"
                                accept="image/png,image/jpeg,image/webp,image/svg+xml,.svg"
                                data-image-input data-image-target="cover">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-flush mb-8">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Contacto</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-6">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sitio web</label>
                            <input type="url" name="website" class="form-control form-control-solid"
                                value="{{ old('website', $profile->website) }}"
                                placeholder="https://www.empresa.com" data-preview-text="website">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email corporativo</label>
                            <input type="email" name="corporate_email" class="form-control form-control-solid"
                                value="{{ old('corporate_email', $profile->corporate_email) }}"
                                placeholder="contacto@empresa.com" data-preview-text="corporate_email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telefono</label>
                            <input type="text" name="phone" class="form-control form-control-solid"
                                value="{{ old('phone', $profile->phone) }}" placeholder="+52 81 1234 5678">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control form-control-solid"
                                value="{{ old('whatsapp', $profile->whatsapp) }}" placeholder="+52 81 1234 5678">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-flush mb-8">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Direccion</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-6">
                        <label class="form-label fw-semibold">Direccion completa</label>
                        <input type="text" name="address" class="form-control form-control-solid"
                            value="{{ old('address', $profile->address) }}" placeholder="Calle, numero, colonia">
                    </div>
                    <div class="row g-6">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Ciudad</label>
                            <input type="text" name="city" class="form-control form-control-solid"
                                value="{{ old('city', $profile->city) }}" placeholder="Monterrey"
                                data-preview-text="city">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Estado</label>
                            <input type="text" name="state" class="form-control form-control-solid"
                                value="{{ old('state', $profile->state) }}" placeholder="Nuevo Leon"
                                data-preview-text="state">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pais</label>
                            <input type="text" name="country" class="form-control form-control-solid"
                                value="{{ old('country', $profile->country) }}" placeholder="Mexico">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-flush mb-8">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Descripcion</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-6">
                        <label class="form-label fw-semibold">Descripcion corta</label>
                        <textarea name="short_description" class="form-control form-control-solid" rows="3" maxlength="150"
                            placeholder="Breve descripcion de la empresa (max. 150 caracteres)"
                            data-preview-text="short_description">{{ old('short_description', $profile->short_description) }}</textarea>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Descripcion larga</label>
                        <textarea name="long_description" class="form-control form-control-solid" rows="8"
                            placeholder="Descripcion detallada de la empresa, historia, valores, etc.">{{ old('long_description', $profile->long_description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card card-flush">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Redes sociales</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-6">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Facebook</label>
                            <input type="url" name="facebook_url" class="form-control form-control-solid"
                                value="{{ old('facebook_url', $profile->facebook_url) }}"
                                placeholder="https://facebook.com/empresa">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Instagram</label>
                            <input type="url" name="instagram_url" class="form-control form-control-solid"
                                value="{{ old('instagram_url', $profile->instagram_url) }}"
                                placeholder="https://instagram.com/empresa">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">LinkedIn</label>
                            <input type="url" name="linkedin_url" class="form-control form-control-solid"
                                value="{{ old('linkedin_url', $profile->linkedin_url) }}"
                                placeholder="https://linkedin.com/company/empresa">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Twitter / X</label>
                            <input type="url" name="twitter_url" class="form-control form-control-solid"
                                value="{{ old('twitter_url', $profile->twitter_url) }}"
                                placeholder="https://twitter.com/empresa">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card card-flush position-sticky top-0">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Vista previa</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="border rounded overflow-hidden">
                        <div class="developer-preview-cover" data-cover-preview
                            @if ($profile->coverImageUrl()) style="background-image: url('{{ $profile->coverImageUrl() }}')" @endif>
                        </div>
                        <div class="px-6 pb-6">
                            @if ($profile->logoUrl())
                                <img src="{{ $profile->logoUrl() }}" alt="Logo" class="developer-preview-logo bg-white rounded shadow-sm" data-logo-preview>
                                <div class="developer-preview-placeholder bg-light-primary rounded shadow-sm d-none align-items-center justify-content-center" data-logo-placeholder>
                                    <i class="ki-outline ki-bank fs-2x text-primary"></i>
                                </div>
                            @else
                                <img src="" alt="Logo" class="developer-preview-logo bg-white rounded shadow-sm d-none" data-logo-preview>
                                <div class="developer-preview-placeholder bg-light-primary rounded shadow-sm d-flex align-items-center justify-content-center" data-logo-placeholder>
                                    <i class="ki-outline ki-bank fs-2x text-primary"></i>
                                </div>
                            @endif
                            <h4 class="fw-bold text-gray-900 mt-5 mb-2" data-preview-output="commercial_name">
                                {{ $profile->commercial_name ?: 'Desarrollos Premium' }}
                            </h4>
                            <div class="text-gray-600 fw-semibold mb-5" data-preview-output="short_description">
                                {{ $profile->short_description ?: 'Empresa lider en desarrollo inmobiliario' }}
                            </div>
                            <div class="separator mb-5"></div>
                            <div class="d-flex flex-column gap-3 text-gray-700 fw-semibold">
                                <div>
                                    <i class="ki-outline ki-geolocation text-danger me-2"></i>
                                    <span data-preview-location>
                                        {{ trim(($profile->city ?: 'Monterrey') . ', ' . ($profile->state ?: 'N.L.'), ' ,') }}
                                    </span>
                                </div>
                                <div>
                                    <i class="ki-outline ki-global text-info me-2"></i>
                                    <span data-preview-output="website">{{ $profile->website ?: 'www.empresa.com' }}</span>
                                </div>
                                <div>
                                    <i class="ki-outline ki-sms text-primary me-2"></i>
                                    <span data-preview-output="corporate_email">{{ $profile->corporate_email ?: 'contacto@empresa.com' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-muted fs-8 mt-4">La vista previa se actualiza mientras capturas la informacion.</div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.querySelector('[data-developer-profile-form]');

            if (!form) {
                return;
            }

            function toast(icon, title) {
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: icon,
                        title: title,
                        showConfirmButton: false,
                        timer: 4200,
                        timerProgressBar: true
                    });
                    return;
                }

                window.alert(title);
            }

            function firstError(response) {
                if (!response || !response.errors) {
                    return response && response.message ? response.message : 'No se pudo guardar la informacion.';
                }

                for (var key in response.errors) {
                    if (Object.prototype.hasOwnProperty.call(response.errors, key) && response.errors[key].length) {
                        return response.errors[key][0];
                    }
                }

                return response.message || 'No se pudo guardar la informacion.';
            }

            function updatePreview(name, value) {
                var output = document.querySelector('[data-preview-output="' + name + '"]');

                if (output) {
                    output.textContent = value || output.dataset.defaultText || output.textContent;
                }

                var city = form.querySelector('[name="city"]')?.value || 'Monterrey';
                var state = form.querySelector('[name="state"]')?.value || 'N.L.';
                var location = document.querySelector('[data-preview-location]');

                if (location) {
                    location.textContent = city + ', ' + state;
                }
            }

            form.querySelectorAll('[data-preview-text]').forEach(function (input) {
                input.addEventListener('input', function () {
                    updatePreview(input.dataset.previewText, input.value);
                });
            });

            function previewImage(input) {
                var file = input.files && input.files[0];

                if (!file) {
                    return;
                }

                var reader = new FileReader();
                reader.onload = function (event) {
                    if (input.dataset.imageTarget === 'logo') {
                        var logo = document.querySelector('[data-logo-preview]');
                        var placeholder = document.querySelector('[data-logo-placeholder]');

                        logo.src = event.target.result;
                        logo.classList.remove('d-none');
                        placeholder.classList.add('d-none');
                        placeholder.classList.remove('d-flex');
                    }

                    if (input.dataset.imageTarget === 'cover') {
                        var cover = document.querySelector('[data-cover-preview]');
                        cover.style.backgroundImage = 'url("' + event.target.result + '")';
                    }
                };
                reader.readAsDataURL(file);
            }

            form.querySelectorAll('[data-image-input]').forEach(function (input) {
                input.addEventListener('change', function () {
                    previewImage(input);
                });
            });

            form.querySelectorAll('[data-file-dropzone]').forEach(function (dropzone) {
                var input = document.getElementById(dropzone.getAttribute('for'));

                ['dragenter', 'dragover'].forEach(function (eventName) {
                    dropzone.addEventListener(eventName, function (event) {
                        event.preventDefault();
                        dropzone.classList.add('is-dragging');
                    });
                });

                ['dragleave', 'drop'].forEach(function (eventName) {
                    dropzone.addEventListener(eventName, function (event) {
                        event.preventDefault();
                        dropzone.classList.remove('is-dragging');
                    });
                });

                dropzone.addEventListener('drop', function (event) {
                    input.files = event.dataTransfer.files;
                    previewImage(input);
                });
            });

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                var submitButton = document.querySelector('[data-developer-profile-submit]');
                var formData = new FormData(form);

                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.setAttribute('data-kt-indicator', 'on');
                }

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
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
                        toast('success', payload.message || 'Informacion guardada correctamente.');

                        window.setTimeout(function () {
                            window.location.href = payload.redirect || '{{ route('admin.developer-profile') }}';
                        }, 900);
                    })
                    .catch(function (error) {
                        toast('error', firstError(error));
                    })
                    .finally(function () {
                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.removeAttribute('data-kt-indicator');
                        }
                    });
            });
        });
    </script>
@endpush
