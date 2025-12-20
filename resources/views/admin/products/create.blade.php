@extends('layouts.app')

@section('title', __('create_product'))
@section('header-title', __('create_product'))

@push('styles')
    <style>
        .spin {
            animation: spin 0.6s linear;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

@section('content')

    <div class="action-bar">

        <!-- Back Button -->
        <button class="btn btn-gold" onclick="window.location.href='/admin/products'">
            ← @lang('back')
        </button>
    </div>

    <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-left">
                <div class="upload-box" id="uploadBox">
                    <input type="file" id="imageInput" name="image" accept="image/*">
                    <img id="previewImage" src="" alt="" class="hidden">
                    <span id="uploadText">{{ __('upload_image') }}</span>
                    <button type="button" class="delete-icon hidden" id="deleteImage">×</button>
                </div>
            </div>

            <div class="col-right">
                <div style="display: flex;">
                    <div style="width: 50%; padding-right: 20px;">
                        <label>@lang('name')<span>*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="@lang('enter_product_name')"
                            required>
                    </div>
                    <div style="position: relative; width: 50%;">
                        <label>@lang('sku')<span>*</span></label>
                        <input type="text" id="generateSKU" name="sku" value="{{ old('sku') }}"
                            placeholder="@lang('generate_sku')" required style="padding-right: 40px;">
                        <!-- Black clean refresh icon -->
                        <span id="barcodeIcon" onclick="generateBarcode()"
                            style="position:absolute; right:15px; bottom:16px; cursor:pointer; display:flex; align-items:center; justify-content:center;"
                            title="Auto-generate barcode">
                            <svg id="refreshSvg" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="23 4 23 10 17 10" />
                                <polyline points="1 20 1 14 7 14" />
                                <path d="M3.51 9a9 9 0 0114.85-3.36L23 10" />
                                <path d="M20.49 15a9 9 0 01-14.85 3.36L1 14" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div style="display: flex;">
                    <div style="width: 50%; padding-right: 20px;">
                        <label>@lang('category')<span>*</span></label>
                        <select name="category_id" required>
                            <button>
                                <selectedcontent></selectedcontent>
                            </button>

                            <option value="">
                                <div class="custom-option">
                                    <span class="option-text">{{ __('select_category') }}</span>
                                </div>
                            </option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    <div class="custom-option">
                                        <span class="option-text">{{ $category->name }}</span>
                                    </div>
                                </option>
                            @endforeach

                        </select>
                    </div>
                    <div style="width: 50%;">
                        <label>@lang('barcode') (@lang('optional'))</label>
                        <input type="text" name="barcode" value="{{ old('barcode') }}"
                            placeholder="@lang('enter_barcode')" required style="padding-right: 40px;">
                    </div>
                </div>

                <label for="description">@lang('description')(@lang('optional'))</label>
                <textarea name="description" rows="4" placeholder="{{ __('enter_product_details') }}">{{ old('description') }}</textarea>

                <br>
                <label for="">{{ __('branches') }}</label>
                @php
                    $branches = auth()->user()->getBranches();
                    $activeBranch = auth()->user()->getActiveBranch();

                    // Move active branch to top
                    if ($activeBranch) {
                        $branches = $branches
                            ->sortByDesc(function ($branch) use ($activeBranch) {
                                return $branch->id === $activeBranch->id ? 1 : 0;
                            })
                            ->values();
                    }
                @endphp
                @foreach ($branches as $branch)
                    <strong>{{ $branch->name }}</strong>
                    <input type="hidden" name="branches[{{ $loop->index }}][branch_id]" value="{{ $branch->id }}">
                    <div style="display: flex; width: 100%;">
                        <div style="width: 25%; padding-right: 20px;">
                            <label>@lang('price')({{ getCurrencySymbolByCurrencyCode($branch->currency_code) }})<span>*</span></label>
                            <input type="number" name="branches[{{ $loop->index }}][price]"
                                value="{{ old('branches.' . $loop->index . '.price') }}" placeholder="@lang('enter_price')"
                                required>
                        </div>
                        <div style="width: 25%; padding-right: 20px;">
                            <label>@lang('cost')({{ getCurrencySymbolByCurrencyCode($branch->currency_code) }})<span>*</span></label>
                            <input type="number" name="branches[{{ $loop->index }}][cost]"
                                value="{{ old('branches.' . $loop->index . '.cost') }}" placeholder="@lang('enter_cost')"
                                required>
                        </div>

                        <div style="width: 25%; padding-right: 20px;">
                            <label>@lang('stock_quantity')<span>*</span></label>
                            <input type="number" name="branches[{{ $loop->index }}][stock_quantity]"
                                value="{{ old('branches.' . $loop->index . '.stock_quantity') }}"
                                placeholder="@lang('enter_stock_quantity')" required>
                        </div>

                        <div style="width: 25%;">
                            <label>@lang('threshold')<span>*</span></label>
                            <input type="number" name="branches[{{ $loop->index }}][threshold]"
                                value="{{ old('branches.' . $loop->index . '.threshold') }}"
                                placeholder="@lang('enter_threshold')" required>
                        </div>
                    </div>
                @endforeach

                <button type="submit" class="submit-btn">@lang('create')</button>
            </div>
        </div>

    </form>

    @push('scripts')
        <script src="{{ asset('js/image.js') }}"></script>
        <script>
            function generateBarcode() {
                const icon = document.getElementById('refreshSvg');

                // Start animation
                icon.classList.add('spin');

                // Remove animation class after finished
                setTimeout(() => {
                    icon.classList.remove('spin');
                }, 300);

                // Generate 13-digit barcode
                let barcode = Math.floor(1000000000000 + Math.random() * 9000000000000);

                document.getElementById('generateSKU').value = barcode;
            }
        </script>
    @endpush

@endsection
