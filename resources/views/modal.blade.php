<!-- ===== Modal ===== -->
<div class="modal-bg" id="modal">
    <div class="modal-box">
        <p id="modal-message">Are you sure you want to continue?</p>
        <br>
        <button class="btn btn-cancel" onclick="closeDialog()">{{ __('cancel') }}</button>
        <button class="btn btn-ok" onclick="confirmAction()">{{ __('ok') }}</button>
    </div>
</div>

<!-- ===== Success Toast ===== -->
<div id="toast" class="toast"></div>
