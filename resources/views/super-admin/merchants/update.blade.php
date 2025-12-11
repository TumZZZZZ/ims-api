@extends('layouts.app')

@section('title', __('edit_merchant'))
@section('header-title', __('edit_merchant'))

@section('content')

    <div class="action-bar">

        <!-- Back Button -->
        <button class="btn btn-gold"
            onclick="window.location.href='/super-admin/merchants'">
            ← @lang('back')
        </button>
    </div>

    <form action="{{ route('super-admin.merchants.update', $data->id) }}" method="POST" enctype="multipart/form-data">
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
                <label class="label-bold">@lang('merchant_information')</label>

                <div>
                    <label>@lang('name')<span>*</span></label>
                    <input type="text" name="merchant_name" value="{{ $data->name }}" placeholder="@lang('enter_merchant_name')" required>
                </div>

                <div>
                    <label >@lang('address')<span>*</span></label>
                    <input type="text" name="merchant_address" value="{{ $data->address }}" placeholder="@lang('enter_merchant_address')" required>
                </div>

                <button type="submit" class="submit-btn">@lang('update')</button>
            </div>
        </div>

    </form>

    @push('scripts')
        <script src="{{ asset('js/image.js') }}"></script>
    @endpush
@endsection
