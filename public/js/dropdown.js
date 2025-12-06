// Language Dropdown
const toggleLanguageBtn = document.getElementById("dropdownLanguageToggle");
const languageMenu = document.getElementById("dropdownLanguageMenu");

toggleLanguageBtn.addEventListener("click", () => {
    languageMenu.classList.toggle("show");
});

document.addEventListener("click", (e) => {
    if (
        !toggleLanguageBtn.contains(e.target) &&
        !languageMenu.contains(e.target)
    ) {
        languageMenu.classList.remove("show");
    }
});

// Setting Dropdown
const toggleSettingBtn = document.getElementById("dropdownSettingToggle");
const settingMenu = document.getElementById("dropdownSettingMenu");

toggleSettingBtn.addEventListener("click", () => {
    settingMenu.classList.toggle("show");
});

document.addEventListener("click", (e) => {
    if (
        !toggleSettingBtn.contains(e.target) &&
        !settingMenu.contains(e.target)
    ) {
        settingMenu.classList.remove("show");
    }
});
