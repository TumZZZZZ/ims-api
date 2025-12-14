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
                    <label>@lang('parent_category') (@lang('optional'))</label>
                    <select name="parent_category_id">
                        <button>
                            <selectedcontent></selectedcontent>
                        </button>

                        <option value="">
                            <div class="custom-option">
                                <span class="option-text">{{ __('select_parent_category') }}</span>
                            </div>
                        </option>

                        @foreach ($parentCategories as $parentCategory)
                            <option value="{{ $parentCategory->id }}">
                                <div class="custom-option">
                                    <span class="option-text">{{ $parentCategory->name }}</span>
                                </div>
                            </option>
                        @endforeach

                    </select>

                </div>

                <button type="submit" class="submit-btn">@lang('create')</button>
            </div>
        </div>

    </form>

    @push('scripts')
        <script src="{{ asset('js/image.js') }}"></script>
    @endpush

@endsection
