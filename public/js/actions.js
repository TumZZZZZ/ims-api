// Show toast from sessionStorage (after page reload)
document.addEventListener("DOMContentLoaded", function () {
    const deleteMerchantMessage = sessionStorage.getItem("delete_message");
    if (deleteMerchantMessage) {
        const toast = document.getElementById("toast");
        toast.innerHTML = deleteMerchantMessage;
        toast.style.display = "block";

        sessionStorage.removeItem("delete_message");

        setTimeout(() => {
            toast.style.display = "none";
        }, 5000);
    }
});

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        openDialog(
            this.dataset.url,
            this.dataset.id,
            this.dataset.name,
            this.dataset.title
        );
    });
});

// Modal
let baseUrl = "";
let currentObjectId = "";
let currentObjectName = "";
let currentAction = "";

function openDialog(url, objectId, objectName, action) {
    baseUrl = url;
    currentObjectId = objectId;
    currentObjectName = objectName;
    currentAction = action;

    // Get the localized string with placeholders from Blade
    let template = window.confirmationTemplate;

    // Replace placeholders with actual values in JS
    let confirmationMessage = template
        .replace(":action", action.toLowerCase())
        .replace(":object_name", objectName);

    document.getElementById("modal-message").innerHTML = confirmationMessage;
    document.getElementById("modal").style.display = "flex";
}

function closeDialog() {
    document.getElementById("modal").style.display = "none";
}

function confirmAction() {
    let action = currentAction.toUpperCase();

    // Handle delete action separately
    if (["DELETE"].includes(action)) {
        const url = `/${baseUrl}/${currentObjectId}?name=${encodeURIComponent(
            currentObjectName
        )}`;

        fetch(url, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json()) // <-- parse JSON
            .then((data) => {
                if (data.success) {
                    closeDialog();

                    // Store message in sessionStorage for reload
                    sessionStorage.setItem("delete_message", data.message);

                    // Reload page
                    window.location.reload();
                } else {
                    alert("Failed to delete category.");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
        return;
    }

    // AJAX request to suspend/activate merchant
    if (['SUSPEND', 'ACTIVATE'].includes(action)) {
        const url = `/${baseUrl}/${currentObjectId}/suspend-or-activate`;

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                name: currentObjectName,
                action: currentAction,
                active: currentAction === "Suspend" ? 0 : 1
            })
        })
        .then(response => {
            if (response.ok) {
                closeDialog();

                // Update columns status & action
                const statusSpan = document.getElementById(`status-${currentObjectId}`);
                const actionBtn = document.getElementById(`action-btn-${currentObjectId}`);

                if (currentAction.toLowerCase() === "suspend") {
                    statusSpan.textContent = "Suspend";
                    statusSpan.style.color = "#FFD700";

                    actionBtn.textContent = "Activate";
                    actionBtn.style.background = "#4CAF50";
                    actionBtn.setAttribute("onclick", `openDialog('${baseUrl}', '${currentObjectId}', '${currentObjectName}', 'activate')`);
                } else {
                    statusSpan.textContent = "Activate";
                    statusSpan.style.color = "#4CAF50";

                    actionBtn.textContent = "Suspend";
                    actionBtn.style.background = "#FFD700";
                    actionBtn.setAttribute("onclick", `openDialog('${baseUrl}', '${currentObjectId}', '${currentObjectName}', 'suspend')`);
                }

                // Display success message
                const toast = document.getElementById("toast");

                // Get the localized string with placeholders from Blade
                let objectActionTemplate = window.objectActionTemplate;

                // Replace placeholders with actual values in JS
                let actionMessage = objectActionTemplate
                    .replace(":action", currentAction.toLowerCase())
                    .replace(":object_name", currentObjectName);

                toast.innerHTML = actionMessage;
                toast.style.display = "block";

                // Disapear after 5 seconds
                setTimeout(() => {
                    toast.style.display = "none";
                }, 5000);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }

    // AJAX request to suspend/activate merchant
    if (['CLOSE','OPEN'].includes(action)) {
        const url = `/${baseUrl}/${currentObjectId}/close-or-open`;

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                name: currentObjectName,
                action: currentAction,
                active: currentAction === "Close" ? 0 : 1
            })
        })
        .then(response => {
            if (response.ok) {
                closeDialog();

                // Update columns status & action
                const statusSpan = document.getElementById(`status-${currentObjectId}`);
                const actionBtn = document.getElementById(`action-btn-${currentObjectId}`);

                if (currentAction.toLowerCase() === "close") {
                    statusSpan.textContent = "Close";
                    statusSpan.style.color = "#FFD700";

                    actionBtn.textContent = "Open";
                    actionBtn.style.background = "#4CAF50";
                    actionBtn.setAttribute("onclick", `openDialog('${baseUrl}', '${currentObjectId}', '${currentObjectName}', 'open')`);
                } else {
                    statusSpan.textContent = "Open";
                    statusSpan.style.color = "#4CAF50";

                    actionBtn.textContent = "Close";
                    actionBtn.style.background = "#FFD700";
                    actionBtn.setAttribute("onclick", `openDialog('${baseUrl}', '${currentObjectId}', '${currentObjectName}', 'close')`);
                }

                // Display success message
                const toast = document.getElementById("toast");

                // Get the localized string with placeholders from Blade
                let objectActionTemplate = window.objectActionTemplate;

                // Replace placeholders with actual values in JS
                let actionMessage = objectActionTemplate
                    .replace(":action", currentAction.toLowerCase())
                    .replace(":object_name", currentObjectName);

                toast.innerHTML = actionMessage;
                toast.style.display = "block";

                // Disapear after 5 seconds
                setTimeout(() => {
                    toast.style.display = "none";
                }, 5000);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }
}
