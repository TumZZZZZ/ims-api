<!-- ===== Modal Form ===== -->
<div class="modal-bg" id="modalRejectPO">
    <div class="modal-box">
        <strong id="modalRejectPOTitle" style="font-size: 24px;"></strong>
        <div style="display: flex; flex-direction: column;">
            <div style="width: 100%; text-align: left; padding-bottom: 10px;">
                <label class="text-left;">@lang('reason')<span>*</span></label>
            </div>
            <div style="width: 100%;">
                <input id="inputReason" type="text" placeholder="@lang('enter_reason')" required>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; padding-top: 20px;">
            <button class="btn btn-cancel" onclick="closeDialogForm()">{{ __('cancel') }}</button>
            <form id="rejectionPOForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input id="inputHiddenReason" type="hidden" name="reason">
                <input id="inputHiddenPONumner" type="hidden" name="po_number">
                <button type="submit" class="btn btn-ok">{{ __('confrim') }}</button>
            </form>
        </div>
    </div>
</div>
<script>
    const reasonInput = document.getElementById("inputReason");
    const hiddenReasonInput = document.getElementById("inputHiddenReason");

    // Trigger whenever user types
    reasonInput.addEventListener("input", function() {
        hiddenReasonInput.value = this.value;
    });

    const formRejection = document.getElementById("rejectionPOForm");
    formRejection.addEventListener('submit', function(e) {
        const reasonValue = hiddenReasonInput.value.trim();
        if (!reasonValue) {
            e.preventDefault(); // Stop form submission
            reasonInput.focus();
        }
    });
</script>
