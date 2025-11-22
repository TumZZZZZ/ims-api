<style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background: #f3f5f9;
    }

    /* ===== Modal Background ===== */
    .modal-bg {
        display: none;
        position: fixed;
        inset: 0;
        backdrop-filter: blur(4px);
        background: rgba(0, 0, 0, 0.35);
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease forwards;
    }

    /* ===== Modal Dialog ===== */
    .modal-box {
        background: #ffffffcc;
        backdrop-filter: blur(8px);
        padding: 28px 30px;
        width: 340px;
        border-radius: 14px;
        text-align: center;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        transform: scale(0.85);
        opacity: 0;
        animation: popUp 0.25s ease forwards;
    }

    @keyframes popUp {
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* ===== Modal Buttons ===== */
    .btn {
        padding: 10px 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        margin: 0 8px;
    }

    .btn-cancel {
        background: #e5e5e5;
        color: #333;
        transition: 0.2s;
    }

    .btn-cancel:hover {
        background: #d3d3d3;
    }

    .btn-ok {
        background: #4CAF50;
        color: #fff;
        transition: 0.2s;
    }

    .btn-ok:hover {
        background: #3d9641;
    }

    /* ===== Success Toast ===== */
    .toast {
        display: none;
        position: fixed;
        top: 25px;
        right: 25px;
        padding: 14px 20px;
        border-radius: 10px;
        background: #e9f9ea;
        color: #1b6e1b;
        font-weight: 600;
        border: 1px solid #b6e5b6;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        animation: slideIn 0.4s ease forwards;
    }

    @keyframes slideIn {
        from {
            transform: translateX(120%);
        }

        to {
            transform: translateX(0);
        }
    }
</style>

<body>

    <button onclick="openDialog()"
        style="
    padding: 10px 20px;
    margin: 40px;
    font-size: 16px;
    border: none;
    background: #4a6cf7;
    color: #fff;
    border-radius: 8px;
    cursor: pointer;
">Open
        Dialog</button>

    <!-- ===== Modal ===== -->
    <div class="modal-bg" id="modal">
        <div class="modal-box">
            <h2 style="margin-top:0;">Confirm Action</h2>
            <p style="opacity:0.8;">Are you sure you want to continue?</p>

            <br>

            <button class="btn btn-cancel" onclick="closeDialog()">Cancel</button>
            <button class="btn btn-ok" onclick="confirmAction()">OK</button>
        </div>
    </div>

    <!-- ===== Success Toast ===== -->
    <div id="toast" class="toast">
        Product saved successfully.
    </div>

    <script>
        function openDialog() {
            document.getElementById("modal").style.display = "flex";
        }

        function closeDialog() {
            document.getElementById("modal").style.display = "none";
        }

        function confirmAction() {
            closeDialog();

            const toast = document.getElementById("toast");
            toast.style.display = "block";

            setTimeout(() => {
                toast.style.display = "none";
            }, 3000);
        }
    </script>

</body>

</html>
