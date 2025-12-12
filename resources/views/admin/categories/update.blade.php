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
                <div class="upload-box" id="uploadBox">
                    <input type="file" id="imageInput" name="image" accept="image/*">
                    <img id="previewImage" src="{{ $data->image_url }}" alt="" class="{{ $data->image_url ? '' : 'hidden' }}">
                    <span id="uploadText" class="{{ $data->image_url ? 'hidden' : '' }}">{{ __('upload_image') }}</span>
                    <button type="button" class="delete-icon {{ $data->image_url ? '' : 'hidden' }}" id="deleteImage">×</button>
                </div>
            </div>

            <div class="col-right">
                <div>
                    <label>@lang('name')<span>*</span></label>
                    <input type="text" name="name" value="{{ $data->name }}"
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
                            <option value="{{ $parentCategory->id }}" {{ $data->parent_id == $parentCategory->id ? 'selected' : '' }}>
                                <div class="custom-option">
                                    <span class="option-text">{{ $parentCategory->name }}</span>
                                </div>
                            </option>
                        @endforeach

                    </select>

                </div>

                <button type="submit" class="submit-btn">@lang('update')</button>
            </div>
        </div>

    </form>

    @push('scripts')
        <script src="{{ asset('js/image.js') }}"></script>
    @endpush

@endsection
