<div class="upload-box" id="uploadBox">
    <input type="file" id="imageInput" name="image" accept="image/*">
    <img id="previewImage" src="{{ @$data->image_url ?? @$data->image->url }}" alt="" class="{{ (@$data->image_url ?? @$data->image->url) ? '' : 'hidden' }}">
    <span id="uploadText" class="{{ (@$data->image_url ?? @$data->image->url) ? 'hidden' : '' }}">{{ __('upload_image') }}</span>
    <button type="button" class="delete-icon {{ (@$data->image_url ?? @$data->image->url) ? '' : 'hidden' }}" id="deleteImage">×</button>
</div>
