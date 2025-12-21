@extends('layouts.app')

@section('title', __('edit_category'))
@section('header-title', __('edit_category'))

@section('content')

    <div class="action-bar">

        <!-- Back Button -->
        <button class="btn btn-gold" onclick="window.location.href='/admin/categories'">
            ← @lang('back')
        </button>
    </div>

    <form action="{{ route('admin.category.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-left">
                @include('layouts.update-image-object')
            </div>

            <div class="col-right">
                <div>
                    <label>@lang('name')<span>*</span></label>
                    <input type="text" name="name" value="{{ $data->name }}"
                        placeholder="@lang('enter_category_name')" required>
                </div>

                <div>
                    <label>@lang('select_product') (@lang('optional'))</label>
                    <div class="custom-multi-select-wrapper" data-placeholder="{{ __('select_product') }}">
                        <button type="button" class="custom-multi-select-btn">
                            <span class="selected-items"></span>
                        </button>

                        <ul class="custom-multi-options">
                            @foreach ($products as $product)
                                <li>
                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" {{ in_array($product->id, $data->product_ids) ? 'checked' : '' }}>
                                    <span class="option-text">{{ $product->name }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>

                <button type="submit" class="submit-btn">@lang('update')</button>
            </div>
        </div>

    </form>

    @push('scripts')
        <script src="{{ asset('js/image.js') }}"></script>
        <script src="{{ asset('js/multi-selection.js') }}"></script>
    @endpush

@endsection
