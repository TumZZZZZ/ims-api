@extends('layouts.app')

@section('title', __('create_branch'))
@section('header-title', __('create_branch'))

@section('content')

    <div class="action-bar">

        <!-- Back Button -->
        <button class="btn btn-gold" onclick="window.location.href='/admin/branches'">
            ← @lang('back')
        </button>
    </div>

    <form action="{{ route('admin.branch.store') }}" method="POST" enctype="multipart/form-data">
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
                        <input type="text" name="name" value="{{ old('name') }}"
                            placeholder="@lang('enter_branch_name')" required>
                    </div>
                    <div style="width: 50%;">
                        <label>@lang('currency')<span>*</span></label>
                        <select name="currency_code" required>
                            <button>
                                <selectedcontent></selectedcontent>
                            </button>
                            <option value="">
                                <div class="custom-option">
                                    <span class="option-text">{{ __('select_currency') }}</span>
                                </div>
                            </option>
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->code }}">
                                    <div class="custom-option">
                                        <span class="option-text">{{ $currency->name }}</span>
                                    </div>
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <label for="address">@lang('address')<span>*</span></label>
                <textarea name="address" rows="4" placeholder="{{ __('enter_address') }}">{{ old('address') }}</textarea>

                <button type="submit" class="submit-btn">@lang('create')</button>
            </div>
        </div>

    </form>

    @push('scripts')
        <script src="{{ asset('js/image.js') }}"></script>
        {{-- <script src="{{ asset('js/multi-selection.js') }}"></script> --}}
        {{-- <script src="{{ asset('js/password.js') }}"></script> --}}
    @endpush

@endsection
