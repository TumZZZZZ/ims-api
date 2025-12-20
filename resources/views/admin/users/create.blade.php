@extends('layouts.app')

@section('title', __('create_user'))
@section('header-title', __('create_user'))

@section('content')

    <div class="action-bar">

        <!-- Back Button -->
        <button class="btn btn-gold" onclick="window.location.href='/admin/users'">
            ← @lang('back')
        </button>
    </div>

    <form id="submitFormUser" action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label>@lang('first_name')<span>*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                            placeholder="@lang('enter_first_name')" required>
                    </div>
                    <div style="width: 50%;">
                        <label>@lang('last_name')<span>*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                            placeholder="@lang('enter_last_name')" required>
                    </div>
                </div>

                <div style="display: flex;">
                    <div style="width: 50%; padding-right: 20px;">
                        <label>@lang('email')<span>*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="@lang('enter_email')"
                            required>
                    </div>
                    <div style="width: 50%;">
                        <label>@lang('phone_number')<span>*</span></label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                            placeholder="@lang('enter_phone_number')" required>
                    </div>
                </div>

                <div style="display: flex;">
                    <div style="width: 50%; padding-right: 20px;">
                        <label>@lang('role')<span>*</span></label>
                        <select name="role">
                            <button>
                                <selectedcontent></selectedcontent>
                            </button>
                            <option value="">
                                <div class="custom-option">
                                    <span class="option-text">{{ __('select_role') }}</span>
                                </div>
                            </option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->key }}">
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
                                        <input type="checkbox" name="branch_ids[]" value="{{ $branch->id }}">
                                        <span class="option-text">{{ $branch->name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>

                <div style="display: flex;">
                    <div style="width: 50%; padding-right: 20px;">
                        <label>@lang('password')<span>*</span></label>
                        <input type="text" id="password" name="password" value="{{ old('password') }}"
                            placeholder="@lang('enter_password')" required>
                        <small id="strength_msg"></small>
                    </div>
                    <div style="width: 50%;">
                        <label>@lang('confirmation_password')<span>*</span></label>
                        <input type="text" id="password_confirmation" name="password_confirmation"
                            value="{{ old('password_confirmation') }}" placeholder="@lang('enter_confirmation_password')" required disabled>
                    </div>
                </div>

                <button type="submit" class="submit-btn">@lang('create')</button>
            </div>
        </div>

    </form>

    @include('modal')

    @push('scripts')
        <script src="{{ asset('js/image.js') }}"></script>
        <script src="{{ asset('js/multi-selection.js') }}"></script>
        <script src="{{ asset('js/password.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const toast = document.getElementById('toast');

                function showToast(message, success = true) {
                    toast.textContent = message;
                    toast.style.color = success ? '#28a745' : '#dc3545';
                    toast.style.border = success ? '1px solid #28a745' : '1px solid #dc3545';
                    toast.style.display = 'block';
                    setTimeout(() => {
                        toast.style.display = 'none';
                    }, 5000);
                }

                const roleSelect = document.querySelector('select[name="role"]');
                const branchCheckboxes = document.querySelectorAll('input[name="branch_ids[]"]');
                const submitFormUser = document.getElementById('submitFormUser');

                function enforceStaffRule(changedCheckbox = null) {
                    const role = roleSelect.value;
                    const checked = Array.from(branchCheckboxes).filter(cb => cb.checked);

                    if (role === 'STAFF') {
                        // If user checks more than one → keep only latest
                        if (checked.length > 1 && changedCheckbox) {
                            checked.forEach(cb => {
                                if (cb !== changedCheckbox) cb.checked = false;
                            });
                        }
                    }
                }

                // When role changes
                roleSelect.addEventListener('change', function () {
                    enforceStaffRule();
                });

                // When branch checkbox changes
                branchCheckboxes.forEach(cb => {
                    cb.addEventListener('change', function () {
                        enforceStaffRule(cb);
                    });
                });

                // Final submit validation (safety)
                submitFormUser.addEventListener('submit', function (e) {
                    const role = roleSelect.value;
                    const checked = Array.from(branchCheckboxes).filter(cb => cb.checked);

                    if (role === 'STAFF' && checked.length !== 1) {
                        e.preventDefault();
                        showToast("{{ __('staff_must_select_exactly_one_branch') }}", false);
                    }
                });

            });
        </script>
    @endpush

@endsection
