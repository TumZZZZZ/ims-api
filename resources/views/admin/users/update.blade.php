@extends('layouts.app')

@section('title', __('update_user'))
@section('header-title', __('update_user'))

@section('content')

    <div class="action-bar">

        <!-- Back Button -->
        <button class="btn btn-gold" onclick="window.location.href='/admin/users'">
            ← @lang('back')
        </button>
    </div>

    <form action="{{ route('admin.user.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-left">
                @include('layouts.update-image-object')
            </div>

            <div class="col-right">
                <div style="display: flex;">
                    <div style="width: 50%; padding-right: 20px;">
                        <label>@lang('first_name')<span>*</span></label>
                        <input type="text" name="first_name" value="{{ $data->first_name }}"
                            placeholder="@lang('enter_first_name')" required>
                    </div>
                    <div style="width: 50%;">
                        <label>@lang('last_name')<span>*</span></label>
                        <input type="text" name="last_name" value="{{ $data->last_name }}"
                            placeholder="@lang('enter_last_name')" required>
                    </div>
                </div>

                <div style="display: flex;">
                    <div style="width: 50%; padding-right: 20px;">
                        <label>@lang('email')<span>*</span></label>
                        <input type="email" name="email" value="{{ $data->email }}"
                            placeholder="@lang('enter_email')" required>
                    </div>
                    <div style="width: 50%;">
                        <label>@lang('phone_number')<span>*</span></label>
                        <input type="text" name="phone_number" value="{{ $data->phone_number }}"
                            placeholder="@lang('enter_phone_number')" required>
                    </div>
                </div>

                <div style="display: flex;">
                    <div style="width: 50%; padding-right: 20px;">
                        <label>@lang('role')<span>*</span></label>
                        <select name="role" disabled>
                            <button>
                                <selectedcontent></selectedcontent>
                            </button>
                            <option value="">
                                <div class="custom-option">
                                    <span class="option-text">{{ __('select_role') }}</span>
                                </div>
                            </option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->key }}" {{ $role->key == $data->role ? 'selected' : '' }}>
                                    <div class="custom-option">
                                        <span class="option-text">{{ $role->value }}</span>
                                    </div>
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 50%;">
                        <label>@lang('branch')<span>*</span></label>
                        <div class="custom-multi-select-wrapper" data-placeholder="{{ __('select_branch') }}">
                            <button type="button" class="custom-multi-select-btn">
                                <span class="selected-items"></span>
                            </button>

                            <ul class="custom-multi-options">
                                @foreach ($branches as $branch)
                                    <li>
                                        <input type="checkbox" name="branch_ids[]" value="{{ $branch->id }}" {{ in_array($branch->id, $data->branch_ids) ? 'checked' : '' }}>
                                        <span class="option-text">{{ $branch->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
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
