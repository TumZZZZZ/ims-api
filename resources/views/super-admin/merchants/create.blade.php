@extends('layouts.app')

@section('title', __('create_merchant'))
@section('header-title', __('create_merchant'))

@section('content')

    <div class="action-bar">

        <!-- Back Button -->
        <button class="btn btn-gold"
            onclick="window.location.href='/super-admin/merchants'">
            ← @lang('back')
        </button>
    </div>

    <form action="{{ route('super-admin.merchants.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-left">
                <div class="upload-box" id="uploadBox">
                    <input type="file" id="imageInput" name="image" accept="image/*">
                    <img id="previewImage" src="" alt="" class="hidden">
                    <span id="uploadText">Upload Image</span>
                    <button type="button" class="delete-icon hidden" id="deleteImage">×</button>
                </div>
            </div>

            <div class="col-right">
                <label class="label-bold">@lang('merchant_information')</label>

                <div>
                    <label>@lang('name')<span>*</span></label>
                    <input type="text" name="merchant_name" value="{{ old('merchant_name') }}" placeholder="@lang('enter_merchant_name')" required>
                </div>

                <div>
                    <label >@lang('address')<span>*</span></label>
                    <input type="text" name="merchant_address" value="{{ old('merchant_address') }}" placeholder="@lang('enter_merchant_address')" required>
                </div>
            </div>
        </div>

        <div class="row mt-20">
            <div class="col-right">
                <label class="label-bold">@lang('user_information')</label>

                <div class="form-row">
                    <div class="flex-1">
                        <label>@lang('first_name')<span>*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="@lang('enter_first_name')" required>
                    </div>
                    <div class="flex-1">
                        <label>@lang('last_name')<span>*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="@lang('enter_last_name')" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="flex-1">
                        <label>@lang('email')<span>*</span></label>
                        <input type="text" name="email" value="{{ old('email') }}" placeholder="@lang('enter_email')" required>
                    </div>
                    <div class="flex-1">
                        <label>@lang('phone_number')<span>*</span></label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="@lang('enter_phone_number')" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="flex-1">
                        <label>@lang('password')<span>*</span></label>
                        <input type="text" id="password" name="password" value="{{ old('password') }}" placeholder="@lang('enter_password')" required>
                        <small id="strength_msg"></small>
                    </div>
                    <div class="flex-1">
                        <label>@lang('confirmation_password')<span>*</span></label>
                        <input type="text" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="@lang('enter_confirmation_password')" required disabled>
                    </div>
                </div>

                <button type="submit" class="submit-btn">@lang('create')</button>
            </div>
        </div>

    </form>

    @push('scripts')
        <script src="{{ asset('js/image.js') }}"></script>
        <script src="{{ asset('js/password.js') }}"></script>
    @endpush
@endsection
