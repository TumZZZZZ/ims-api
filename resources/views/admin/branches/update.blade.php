@extends('layouts.app')

@section('title', __('update_branch'))
@section('header-title', __('update_branch'))

@section('content')

    <div class="action-bar">

        <!-- Back Button -->
        <button class="btn btn-gold" onclick="window.location.href='/admin/branches'">
            ← @lang('back')
        </button>
    </div>

    <form action="{{ route('admin.branch.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-left">
                @include('layouts.update-image-object')
            </div>

            <div class="col-right">
                <div style="display: flex;">
                    <div style="width: 50%; padding-right: 20px;">
                        <label>@lang('name')<span>*</span></label>
                        <input type="text" name="name" value="{{ $data->name }}"
                            placeholder="@lang('enter_branch_name')" required>
                    </div>
                    <div style="width: 50%;">
                        <label>@lang('currency')<span>*</span></label>
                        <select name="currency_code" disabled>
                            <button>
                                <selectedcontent></selectedcontent>
                            </button>
                            <option value="">
                                <div class="custom-option">
                                    <span class="option-text">{{ __('select_currency') }}</span>
                                </div>
                            </option>
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->code }}" {{ $data->currency_code == $currency->code ? 'selected' : '' }}>
                                    <div class="custom-option">
                                        <span class="option-text">{{ $currency->name }}</span>
                                    </div>
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <label for="address">@lang('address')<span>*</span></label>
                <textarea name="address" rows="4" placeholder="{{ __('enter_address') }}">{{ $data->location }}</textarea>

                <button type="submit" class="submit-btn">@lang('update')</button>
            </div>
        </div>

    </form>

    @push('scripts')
        <script src="{{ asset('js/image.js') }}"></script>
    @endpush

@endsection
