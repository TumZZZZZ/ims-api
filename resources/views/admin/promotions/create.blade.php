@extends('layouts.app')

@section('title', __('create_promotion'))
@section('header-title', __('create_promotion'))

@section('content')

    <div class="action-bar">

        <!-- Back Button -->
        <button class="btn btn-gold" onclick="window.location.href='/admin/promotions'">
            ← @lang('back')
        </button>
    </div>

    <form action="{{ route('admin.promotion.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-right">
                <div style="display: flex;">
                    <div style="width: 50%; padding-right: 20px;">
                        <label>@lang('name')<span>*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            placeholder="@lang('enter_promotion_name')" required>
                    </div>
                    <div style="width: 25%; padding-right: 20px;">
                        <label>@lang('type')<span>*</span></label>
                        <select name="type" required>
                            <button>
                                <selectedcontent></selectedcontent>
                            </button>
                            @foreach ($promotion_types as $promotionType)
                                <option value="{{ $promotionType->key }}">
                                    <div class="custom-option">
                                        <span class="option-text">{{ $promotionType->value }}</span>
                                    </div>
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 25%;">
                        <label for="">@lang('amount')<span>*</span></label>
                        <input type="number" name="amount" value="{{ old('amount') }}"
                            placeholder="@lang('enter_amount')" required>
                    </div>
                </div>

                <div style="display: flex;">
                    <div style="width: 50%; padding-right: 20px;">
                        <label>@lang('start_date')<span>*</span></label>
                        <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required>
                    </div>
                    <div style="width: 50%;">
                        <label>@lang('end_date')<span>*</span></label>
                        <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" required>
                    </div>
                </div>

                <div style="display: flex;">
                    <div style="width: 50%; padding-right: 20px;">
                        <label>@lang('product') (@lang('optional'))</label>
                        <div class="custom-multi-select-wrapper" data-placeholder="{{ __('select_product') }}">
                            <button type="button" class="custom-multi-select-btn">
                                <span class="selected-items"></span>
                            </button>

                            <ul class="custom-multi-options">
                                @foreach ($products as $product)
                                    <li>
                                        <input type="checkbox" name="product_ids[]" value="{{ $product->id }}">
                                        <span class="option-text">{{ $product->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div style="width: 50%;">
                        <label>@lang('category') (@lang('optional'))</label>
                        <div class="custom-multi-select-wrapper" data-placeholder="{{ __('select_category') }}">
                            <button type="button" class="custom-multi-select-btn">
                                <span class="selected-items"></span>
                            </button>

                            <ul class="custom-multi-options">
                                @foreach ($categories as $category)
                                    <li>
                                        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}">
                                        <span class="option-text">{{ $category->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-btn">@lang('create')</button>
            </div>
        </div>

    </form>

    @push('scripts')
        <script src="{{ asset('js/image.js') }}"></script>
        <script src="{{ asset('js/multi-selection.js') }}"></script>
    @endpush

@endsection
