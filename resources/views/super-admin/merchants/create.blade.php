@extends('layouts.app')

@section('title', __('merchants'))
@section('header-title', __('merchants'))

@section('content')

    <!-- Action bar -->
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 25px; flex-wrap: wrap;">

        <!-- Back Button -->
        <button class="btn btn-gold"
            onclick="window.location.href='/super-admin/merchants'">
            ← @lang('back')
        </button>
    </div>

    <form id="categoryForm">

        <div class="row">
            <div class="col-left">
                <div class="upload-box" id="uploadBox">
                    <input type="file" id="imageInput" accept="image/*">
                    <img id="previewImage" src="" alt="" class="hidden">
                    <span id="uploadText">Upload Image</span>
                    <button type="button" class="delete-icon hidden" id="deleteImage">×</button>
                </div>
            </div>

            <div class="col-right">
                <label class="label-bold">@lang('merchant_information')</label>

                <div>
                    <label>@lang('name')<span>*</span></label>
                    <input type="text" name="merchant_name" placeholder="@lang('enter_merchant_name')" required>
                </div>

                <div>
                    <label >@lang('address')<span>*</span></label>
                    <input type="text" name="merchant_address" placeholder="@lang('enter_merchant_address')" required>
                </div>
            </div>
        </div>

        <div class="row mt-20">
            <div class="col-right">
                <label class="label-bold">@lang('user_information')</label>

                <div class="form-row">
                    <div class="flex-1">
                        <label>@lang('first_name')<span>*</span></label>
                        <input type="text" name="first_name" placeholder="@lang('enter_first_name')" required>
                    </div>
                    <div class="flex-1">
                        <label>@lang('last_name')<span>*</span></label>
                        <input type="text" name="last_name" placeholder="@lang('enter_last_name')" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="flex-1">
                        <label>@lang('email')<span>*</span></label>
                        <input type="text" name="email" placeholder="@lang('enter_email')" required>
                    </div>
                    <div class="flex-1">
                        <label>@lang('phone_number')<span>*</span></label>
                        <input type="text" name="phone_number" placeholder="@lang('enter_phone_number')" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="flex-1">
                        <label>@lang('password')<span>*</span></label>
                        <input type="text" name="password" placeholder="@lang('enter_password')" required>
                    </div>
                    <div class="flex-1">
                        <label>@lang('confirmation_password')<span>*</span></label>
                        <input type="text" name="password_confirmation" placeholder="@lang('enter_confirmation_password')" required>
                    </div>
                </div>

                <button type="submit" class="submit-btn">@lang('create')</button>
            </div>
        </div>

    </form>

    @push('styles')
        <style>

            .btn-gold {
                background-color: var(--gold);
            }

            .row {
                display: flex;
                gap: 20px;
                margin-bottom: 20px;
                flex-wrap: wrap;
            }

            .mt-20 {
                margin-top: 20px;
            }

            .col-left {
                flex: 0 0 150px;
                text-align: center;
            }

            .col-right {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .form-row {
                display: flex;
                gap: 12px;
            }

            .flex-1 {
                flex: 1;
            }

            .label-bold {
                display: block;
                font-weight: 600;
                margin-bottom: 4px;
                font-size: 20px;
            }

            label span {
                color: red;
            }

            .upload-box {
                border: 2px dashed #ccc;
                border-radius: 12px;
                width: 150px;
                height: 150px;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                color: #888;
            }

            .upload-box:hover {
                border-color: var(--gold);
            }

            .upload-box img {
                width: 150px;
                height: 150px;
                object-fit: cover;
                border-radius: 10px;
            }

            .hidden {
                display: none !important;
            }

            .upload-box input[type="file"] {
                position: absolute;
                inset: 0;
                opacity: 0;
                cursor: pointer;
            }

            .delete-icon {
                position: absolute;
                top: 8px;
                right: 8px;
                background: var(--gold);
                color: white;
                border: none;
                border-radius: 50%;
                width: 24px;
                height: 24px;
                cursor: pointer;
            }

            input[type="text"], select {
                width: 100%;
                padding: 10px 12px;
                border: 1px solid #ccc;
                border-radius: 6px;
            }

            .submit-btn {
                background: #4CAF50;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 6px;
                cursor: pointer;
                align-self: flex-end;
                margin-top: 10px;
            }

            @media (max-width: 768px) {
                .row {
                    flex-direction: column;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            const imageInput = document.getElementById('imageInput');
            const previewImage = document.getElementById('previewImage');
            const uploadText = document.getElementById('uploadText');
            const deleteImage = document.getElementById('deleteImage');

            imageInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file && file.size <= 5 * 1024 * 1024) {
                    const reader = new FileReader();
                    reader.onload = () => {
                        previewImage.src = reader.result;
                        previewImage.classList.remove('hidden');
                        uploadText.classList.add('hidden');
                        deleteImage.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert("Image must be less than 5MB.");
                    imageInput.value = "";
                }
            });

            deleteImage.addEventListener('click', () => {
                imageInput.value = "";
                previewImage.src = "";
                previewImage.classList.add('hidden');
                uploadText.classList.remove('hidden');
                deleteImage.classList.add('hidden');
            });

            document.getElementById("categoryForm").addEventListener("submit", function(e) {
                e.preventDefault();
                alert("Form submitted — integrate with Laravel controller here.");
            });
        </script>
    @endpush

@endsection
