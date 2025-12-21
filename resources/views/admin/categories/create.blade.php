@extends('layouts.app')

@section('title', __('create_category'))
@section('header-title', __('create_category'))

@section('content')

    <div class="action-bar">

        <!-- Back Button -->
        <button class="btn btn-gold" onclick="window.location.href='/admin/categories'">
            ← @lang('back')
        </button>
    </div>

    <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
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
                <div>
                    <label>@lang('name')<span>*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
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
                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}">
                                    <span class="option-text">{{ $product->name }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>

                <button type="submit" class="submit-btn">@lang('create')</button>
            </div>
        </div>

    </form>

    <!-- ===== Modal ===== -->
    @include('modal')

    @push('scripts')
        <script src="{{ asset('js/multi-selection.js') }}"></script>
        <script src="{{ asset('js/image.js') }}"></script>
        {{-- Check if Laravel has a error message --}}
        @if(session('error_message'))
            <script>
                const toast = document.getElementById("toast");
                toast.innerHTML = @json(session('error_message'));
                toast.style.display = "block";
                toast.style.color = "#FF0000";
                toast.style.border = "1px solid #FF0000";

                setTimeout(() => {
                    toast.style.display = "none";
                }, 5000);
            </script>
        @endif

    @endpush

@endsection
