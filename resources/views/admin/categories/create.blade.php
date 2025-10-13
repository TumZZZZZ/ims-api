@extends('layouts.app')

@section('title', 'Categories')
@section('header-title', 'Categories')

@section('content')

    <!-- Action bar: Search + Buttons -->
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 25px; flex-wrap: wrap;">

        <!-- Buttons -->
        <button style="background-color: var(--gold); color:white; padding:10px 18px; border:none; border-radius:6px;"
            onclick="window.location.href='/admin/category-list'">
            ← Back
        </button>
    </div>

    <form id="categoryForm">

        <div class="row">
            <div class="col-left">
                <div class="upload-box" id="uploadBox">
                    <input type="file" id="imageInput" accept="image/*">
                    <img id="previewImage" src="" alt="" style="display:none;">
                    <span id="uploadText">Upload Image</span>
                    <button type="button" class="delete-icon" id="deleteImage">×</button>
                </div>
                <small id="fileName" style="display:block; text-align:center;"></small>
            </div>
            <div class="col-right">
                <div class="form-group">
                    <label for="parent">Name</label>
                    <input type="text" placeholder="Enter category name">
                </div>

                <div class="form-group">
                    <label for="parent">Parent Category (Optional)</label>
                    <select id="parent" name="parent_id">
                        <option value="">-- None --</option>
                        <option value="1">Beverages</option>
                        <option value="2">Snacks</option>
                    </select>
                </div>

                <button type="submit" class="submit-btn">Save</button>
            </div>
        </div>

    </form>

    <style>
        .row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
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
            display: none;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 4px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .submit-btn {
            background: var(--dark);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            align-self: flex-end;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .row {
                flex-direction: column;
            }

            .col-left,
            .col-right {
                flex: 1;
            }
        }
    </style>

    <script>
        const imageInput = document.getElementById('imageInput');
        const previewImage = document.getElementById('previewImage');
        const uploadText = document.getElementById('uploadText');
        const deleteImage = document.getElementById('deleteImage');
        const fileNameDisplay = document.getElementById('fileName');

        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file && file.size <= 5 * 1024 * 1024) {
                const reader = new FileReader();
                reader.onload = () => {
                    previewImage.src = reader.result;
                    previewImage.style.display = 'block';
                    uploadText.style.display = 'none';
                    deleteImage.style.display = 'block';
                    fileNameDisplay.textContent = file.name;
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
            previewImage.style.display = 'none';
            uploadText.style.display = 'block';
            deleteImage.style.display = 'none';
            fileNameDisplay.textContent = '';
        });

        document.getElementById("categoryForm").addEventListener("submit", function(e) {
            e.preventDefault();
            alert("Form submitted — integrate with Laravel controller here.");
        });
    </script>

@endsection
