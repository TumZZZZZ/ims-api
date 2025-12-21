// Image upload preview and delete functionality
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
