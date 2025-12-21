// Dropdown utility function
function initializeDropdown(toggleId, menuId) {
    const toggleBtn = document.getElementById(toggleId);
    const menu = document.getElementById(menuId);

    if (!toggleBtn || !menu) return;

    toggleBtn.addEventListener("click", () => {
        menu.classList.toggle("show");
    });

    document.addEventListener("click", (e) => {
        if (!toggleBtn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove("show");
        }
    });
}

// Initialize dropdowns
initializeDropdown("dropdownBranchToggle", "dropdownBranchMenu");
initializeDropdown("dropdownLanguageToggle", "dropdownLanguageMenu");
initializeDropdown("dropdownSettingToggle", "dropdownSettingMenu");
