@extends('layouts.admin')

@section('title', 'Comparador de propiedades | Biblia Inmobiliaria')

@section('toolbar')
    <div>
        <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
            Comparador de propiedades
        </h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">{{ $selectedCount }} de {{ $comparisonMax }} propiedades seleccionadas</li>
        </ul>
    </div>
    <div class="d-flex gap-3">
        <a href="{{ route('admin.advisor-catalog.index') }}" class="btn btn-light">
            <i class="ki-outline ki-arrow-left fs-2"></i>
            Catalogo
        </a>
        @if ($selectedCount > 0)
            <button type="button" class="btn btn-primary" data-print-comparison>
                <i class="ki-outline ki-file-down fs-2"></i>
                Exportar PDF
            </button>
            <form method="POST" action="{{ route('admin.development-comparison.selection.clear') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-light-danger">
                    <i class="ki-outline ki-cross fs-2"></i>
                    Limpiar todo
                </button>
            </form>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .comparison-shell {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .comparison-summary {
            border-radius: .75rem;
        }

        .comparison-table {
            min-width: max(100%, 920px);
        }

        .comparison-table th,
        .comparison-table td {
            min-width: 270px;
            vertical-align: middle;
        }

        .comparison-table .comparison-feature-col {
            position: sticky;
            left: 0;
            min-width: 260px;
            width: 260px;
            z-index: 4;
            background: #fff;
            box-shadow: 12px 0 18px -18px rgba(15, 23, 42, .45);
        }

        .comparison-table thead .comparison-feature-col,
        .comparison-section-row .comparison-feature-col {
            background: #f5f7fb;
            z-index: 5;
        }

        .comparison-property-thumb {
            width: 48px;
            height: 48px;
            border-radius: .55rem;
            object-fit: cover;
            flex: 0 0 auto;
        }

        .comparison-remove {
            width: 30px;
            height: 30px;
        }

        .comparison-check {
            width: 22px;
            height: 22px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .comparison-price-row td {
            background: #eef6ff;
        }

        .comparison-commission-row td {
            background: #eafaf2;
        }

        .comparison-cell-multiline {
            white-space: pre-line;
        }

        @media print {
            .app-sidebar,
            .app-toolbar,
            .app-footer,
            .comparison-remove {
                display: none !important;
            }

            .app-main {
                margin-left: 0 !important;
            }

            .comparison-table .comparison-feature-col {
                position: static;
                box-shadow: none;
            }
        }
    </style>
@endpush

@section('content')
    <div class="app-container container-fluid comparison-shell">
        <div class="card card-flush comparison-summary">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-4 py-6">
                <div>
                    <h3 class="fw-bold text-gray-900 mb-1">Comparador de propiedades</h3>
                    <div class="text-muted fw-semibold fs-7">
                        {{ $selectedCount }} de {{ $comparisonMax }} propiedades
                        @if ($selectedCount > 0 && $selectedCount < $comparisonMin)
                            · selecciona al menos {{ $comparisonMin }} para comparar mejor
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('admin.advisor-catalog.index') }}" class="btn btn-light-primary">
                        <i class="ki-outline ki-plus fs-2"></i>
                        Agregar desarrollos
                    </a>
                </div>
            </div>
        </div>

        @if ($developments->isEmpty())
            <div class="card card-flush">
                <div class="card-body text-center py-15">
                    <i class="ki-outline ki-switch fs-3x text-gray-400 mb-4"></i>
                    <div class="fw-bold fs-3 text-gray-900">Sin desarrollos seleccionados.</div>
                    <div class="text-muted fw-semibold mt-2">Marca entre {{ $comparisonMin }} y {{ $comparisonMax }} desarrollos desde el catalogo.</div>
                    <a href="{{ route('admin.advisor-catalog.index') }}" class="btn btn-primary mt-6">
                        Ir al catalogo
                    </a>
                </div>
            </div>
        @else
            <div class="card card-flush">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-row-bordered align-middle fs-6 mb-0 comparison-table">
                            <thead>
                                <tr class="fw-bold text-gray-900 bg-light">
                                    <th class="comparison-feature-col ps-6">Caracteristica</th>
                                    @foreach ($developments as $development)
                                        <th>
                                            <div class="d-flex align-items-start justify-content-between gap-4 py-2">
                                                <div class="d-flex align-items-center gap-3 min-w-0">
                                                    @if ($development->displayImageUrl())
                                                        <img src="{{ $development->displayImageUrl() }}" alt="{{ $development->name }}" class="comparison-property-thumb">
                                                    @else
                                                        <div class="comparison-property-thumb bg-light-primary d-flex align-items-center justify-content-center">
                                                            <i class="ki-outline ki-home-2 fs-2 text-primary"></i>
                                                        </div>
                                                    @endif
                                                    <div class="min-w-0">
                                                        <a href="{{ route('admin.developments.show', $development) }}" class="text-gray-900 text-hover-primary fw-bold text-truncate d-block">
                                                            {{ $development->name }}
                                                        </a>
                                                        <div class="text-muted fs-8 text-truncate">{{ $development->developerName() }}</div>
                                                    </div>
                                                </div>
                                                <form method="POST" action="{{ route('admin.development-comparison.selection.remove', $development) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-sm btn-light-danger comparison-remove" title="Quitar">
                                                        <i class="ki-outline ki-cross fs-3"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-700">
                                @foreach ($sections as $section)
                                    @if ($section['title'])
                                        <tr class="comparison-section-row">
                                            <td class="comparison-feature-col ps-6 fw-bold text-gray-900">{{ $section['title'] }}</td>
                                            @foreach ($developments as $development)
                                                <td class="bg-light"></td>
                                            @endforeach
                                        </tr>
                                    @endif

                                    @foreach ($section['rows'] as $row)
                                        <tr @class([
                                            'comparison-price-row' => $row['variant'] === 'price',
                                            'comparison-commission-row' => $row['variant'] === 'commission',
                                        ])>
                                            <td class="comparison-feature-col ps-6 fw-bold text-gray-800">{{ $row['label'] }}</td>
                                            @foreach ($developments as $development)
                                                @php($value = $row['values'][$development->id] ?? '-')
                                                <td @class([
                                                    'fw-bold text-primary fs-4' => $row['variant'] === 'price',
                                                    'fw-bold text-success fs-4' => $row['variant'] === 'commission',
                                                    'text-center' => $row['variant'] === 'boolean',
                                                    'comparison-cell-multiline' => is_string($value) && str_contains($value, "\n"),
                                                ])>
                                                    @if ($row['variant'] === 'boolean')
                                                        @if ($value)
                                                            <span class="comparison-check bg-light-success text-success">
                                                                <i class="ki-outline ki-check fs-3"></i>
                                                            </span>
                                                        @else
                                                            <span class="comparison-check bg-light text-gray-400">
                                                                <i class="ki-outline ki-cross fs-3"></i>
                                                            </span>
                                                        @endif
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('[data-print-comparison]')?.addEventListener('click', function () {
                window.print();
            });
        });
    </script>
@endpush
