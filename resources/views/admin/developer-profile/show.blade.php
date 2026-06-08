@extends('layouts.admin')

@section('title', $profile->commercial_name . ' | Biblia Inmobiliaria')

@push('styles')
    <style>
        .developer-show-cover {
            min-height: 220px;
            border-radius: .75rem .75rem 0 0;
            background: linear-gradient(135deg, #4f46e5, #a855f7);
            background-size: cover;
            background-position: center;
        }

        .developer-show-logo {
            width: 96px;
            height: 96px;
            object-fit: contain;
            border: 8px solid var(--bs-body-bg);
            margin-top: -48px;
        }
    </style>
@endpush

@section('toolbar')
    <div>
        <a href="{{ route('admin.developer-profile') }}" class="text-muted text-hover-primary fw-semibold d-inline-flex align-items-center mb-3">
            <i class="ki-outline ki-arrow-left fs-3 me-1"></i>
            Volver a desarrolladoras
        </a>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            {{ $profile->commercial_name }}
        </h1>
        <div class="text-muted fw-semibold fs-6 mt-1">{{ $profile->legal_name }}</div>
    </div>
    <a href="{{ route('admin.developer-profile.edit', $profile) }}" class="btn btn-primary">
        <i class="ki-outline ki-pencil fs-2"></i>
        Editar
    </a>
@endsection

@section('content')
    <div class="row g-8">
        <div class="col-xl-4">
            <div class="card card-flush overflow-hidden">
                <div class="developer-show-cover"
                    @if ($profile->coverImageUrl()) style="background-image: url('{{ $profile->coverImageUrl() }}')" @endif>
                </div>
                <div class="card-body pt-0">
                    @if ($profile->logoUrl())
                        <img src="{{ $profile->logoUrl() }}" alt="{{ $profile->commercial_name }}" class="developer-show-logo rounded bg-white shadow-sm">
                    @else
                        <div class="developer-show-logo rounded bg-light-primary shadow-sm d-flex align-items-center justify-content-center">
                            <i class="ki-outline ki-bank fs-2x text-primary"></i>
                        </div>
                    @endif

                    <h2 class="fw-bold text-gray-900 mt-5 mb-2">{{ $profile->commercial_name }}</h2>
                    <div class="text-muted fw-semibold mb-6">{{ $profile->short_description ?: 'Sin descripcion corta.' }}</div>

                    <div class="separator mb-6"></div>

                    <div class="d-flex flex-column gap-4 fw-semibold text-gray-700">
                        <div><i class="ki-outline ki-geolocation text-danger me-2"></i>{{ trim(($profile->city ?: 'Sin ciudad') . ', ' . $profile->state, ' ,') }}</div>
                        <div><i class="ki-outline ki-global text-info me-2"></i>{{ $profile->website ?: 'Sin sitio web' }}</div>
                        <div><i class="ki-outline ki-sms text-primary me-2"></i>{{ $profile->corporate_email ?: 'Sin email' }}</div>
                        <div><i class="ki-outline ki-phone text-success me-2"></i>{{ $profile->phone ?: 'Sin telefono' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card card-flush mb-8">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="fw-bold mb-0">Informacion general</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-6">
                        <div class="col-md-6">
                            <div class="text-muted fw-semibold fs-7">Nombre comercial</div>
                            <div class="fw-bold text-gray-900">{{ $profile->commercial_name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted fw-semibold fs-7">Razon social</div>
                            <div class="fw-bold text-gray-900">{{ $profile->legal_name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted fw-semibold fs-7">WhatsApp</div>
                            <div class="fw-bold text-gray-900">{{ $profile->whatsapp ?: '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted fw-semibold fs-7">Ultima actualizacion</div>
                            <div class="fw-bold text-gray-900">{{ $profile->updated_at?->format('d/m/Y H:i') }}</div>
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
                    <div class="fw-bold text-gray-900 mb-2">{{ $profile->address ?: 'Sin direccion capturada.' }}</div>
                    <div class="text-muted fw-semibold">
                        {{ collect([$profile->city, $profile->state, $profile->country])->filter()->join(', ') ?: 'Sin ubicacion capturada.' }}
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
                    <div class="text-gray-700 fw-semibold" style="white-space: pre-line;">
                        {{ $profile->long_description ?: 'Sin descripcion larga capturada.' }}
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
                        @foreach ([
                            'Facebook' => $profile->facebook_url,
                            'Instagram' => $profile->instagram_url,
                            'LinkedIn' => $profile->linkedin_url,
                            'Twitter / X' => $profile->twitter_url,
                        ] as $label => $url)
                            <div class="col-md-6">
                                <div class="text-muted fw-semibold fs-7">{{ $label }}</div>
                                @if ($url)
                                    <a href="{{ $url }}" target="_blank" rel="noopener" class="fw-bold text-hover-primary">{{ $url }}</a>
                                @else
                                    <div class="fw-bold text-gray-900">-</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
