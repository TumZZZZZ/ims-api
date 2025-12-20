@extends('layouts.app')

@section('title', __('update_supplier'))
@section('header-title', __('update_supplier'))

@section('content')

    <div class="action-bar">

        <!-- Back Button -->
        <button class="btn btn-gold" onclick="window.location.href='/inventory/suppliers'">
            ← @lang('back')
        </button>
    </div>

    <form action="{{ route('inventory.supplier.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-right">
                <div style="display: flex;">
                    <div style="width: 40%; padding-right: 20px;">
                        <label>@lang('name')<span>*</span></label>
                        <input type="text" name="name" value="{{ $data->name }}"
                            placeholder="@lang('enter_supplier_name')" required>
                    </div>
                    <div style="width: 30%; padding-right: 20px;">
                        <label>@lang('email')<span>*</span></label>
                        <input type="email" name="email" value="{{ $data->email }}"
                            placeholder="@lang('enter_email')" required>
                    </div>
                    <div style="width: 30%;">
                        <label>@lang('phone_number')<span>*</span></label>
                        <input type="text" name="phone_number" value="{{ $data->phone_number }}"
                            placeholder="@lang('enter_phone_number')" required>
                    </div>
                </div>

                <label for="address">@lang('address')<span>*</span></label>
                <textarea name="address" rows="4" placeholder="{{ __('enter_address') }}">{{ $data->address }}</textarea>

                <button type="submit" class="submit-btn">@lang('update')</button>
            </div>
        </div>

    </form>

    @push('scripts')
        <script src="{{ asset('js/image.js') }}"></script>
    @endpush

@endsection
