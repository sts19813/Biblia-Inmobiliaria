@php
    $product = is_array($product ?? null) ? $product : [];
    $indexValue = (string) $index;
    $isRealIndex = is_numeric($indexValue);
    $fieldPrefix = 'property_details[' . $indexValue . ']';
    $fieldIdPrefix = 'property_details_' . $type . '_' . $indexValue;
    $productName = $product['product_name'] ?? '';
    $productValue = fn (string $key) => $product[$key] ?? '';
    $productError = fn (string $key) => $isRealIndex ? 'property_details.' . $indexValue . '.' . $key : null;
@endphp

<div class="development-product-item border border-dashed border-gray-300 rounded p-5" data-product-item>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-5">
        <div>
            <div class="fw-bold fs-5 text-gray-900" data-product-title>
                {{ filled($productName) ? $productName : 'Producto ' . ((int) $indexValue + 1) }}
            </div>
            <div class="text-muted fw-semibold fs-8">Ficha de producto</div>
        </div>
        <button type="button" class="btn btn-sm btn-light-danger" data-product-remove>
            <i class="ki-outline ki-trash fs-3"></i>
            Quitar
        </button>
    </div>

    <div class="row g-5">
        @php
            $productNameError = $productError('product_name');
        @endphp
        <div class="col-md-6 col-xl-4">
            <label class="required form-label" for="{{ $fieldIdPrefix }}_product_name" data-product-label-for="product_name">
                Nombre del producto
            </label>
            <input id="{{ $fieldIdPrefix }}_product_name" type="text" name="{{ $fieldPrefix }}[product_name]"
                value="{{ $productName }}"
                class="form-control form-control-solid @if ($productNameError) @error($productNameError) is-invalid @enderror @endif"
                data-product-input data-product-required="1" data-product-field="product_name"
                @disabled(! $isActiveSection) @required($isActiveSection)>
            @if ($productNameError)
                @error($productNameError)
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            @endif
        </div>

        @foreach ($section['fields'] as $field)
            @php
                $fieldError = $productError($field['name']);
                $fieldId = $fieldIdPrefix . '_' . $field['name'];
                $fieldName = $fieldPrefix . '[' . $field['name'] . ']';
                $colClass = $field['cols'] ?? 'col-md-6 col-xl-4';
            @endphp

            <div class="{{ $colClass }}">
                <label class="form-label" for="{{ $fieldId }}" data-product-label-for="{{ $field['name'] }}">
                    {{ $field['label'] }}
                </label>

                @if ($field['type'] === 'select')
                    <select id="{{ $fieldId }}" name="{{ $fieldName }}"
                        class="form-select form-select-solid @if ($fieldError) @error($fieldError) is-invalid @enderror @endif"
                        data-product-input data-product-required="0" data-product-field="{{ $field['name'] }}"
                        @disabled(! $isActiveSection)>
                        <option value="">Seleccionar</option>
                        @foreach ($field['options'] as $optionValue => $optionLabel)
                            <option value="{{ $optionValue }}" @selected($isSelectedProductOption($product, $field['name'], (string) $optionValue, (string) $optionLabel))>
                                {{ $optionLabel }}
                            </option>
                        @endforeach
                    </select>
                @elseif ($field['type'] === 'textarea')
                    <textarea id="{{ $fieldId }}" name="{{ $fieldName }}" rows="3"
                        class="form-control form-control-solid @if ($fieldError) @error($fieldError) is-invalid @enderror @endif"
                        data-product-input data-product-required="0" data-product-field="{{ $field['name'] }}"
                        @disabled(! $isActiveSection)>{{ $productValue($field['name']) }}</textarea>
                @else
                    <input id="{{ $fieldId }}" type="{{ $field['type'] }}" name="{{ $fieldName }}"
                        value="{{ $productValue($field['name']) }}"
                        class="form-control form-control-solid @if ($fieldError) @error($fieldError) is-invalid @enderror @endif"
                        min="0" step="{{ $field['step'] ?? '1' }}"
                        data-product-input data-product-required="0" data-product-field="{{ $field['name'] }}"
                        @disabled(! $isActiveSection)>
                @endif

                @if ($fieldError)
                    @error($fieldError)
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                @endif
            </div>
        @endforeach
    </div>
</div>
