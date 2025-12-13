document.addEventListener("DOMContentLoaded", function () {
    const wrapper = document.querySelector(".custom-multi-select-wrapper");
    const button = wrapper.querySelector(".custom-multi-select-btn");
    const options = wrapper.querySelector(".custom-multi-options");
    const selectedItems = button.querySelector(".selected-items");

    // Toggle dropdown with animation
    button.addEventListener("click", () => {
        options.classList.toggle("open");
    });

    // Close dropdown if clicked outside
    document.addEventListener("click", (e) => {
        if (!wrapper.contains(e.target)) {
            options.classList.remove("open");
        }
    });

    // Make entire li clickable
    options.querySelectorAll("li").forEach((li) => {
        li.addEventListener("click", () => {
            const checkbox = li.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            li.classList.toggle("selected", checkbox.checked);

            // Update button text
            const selected = Array.from(
                options.querySelectorAll('input[type="checkbox"]')
            )
                .filter((i) => i.checked)
                .map((i) => i.nextElementSibling.textContent.trim());
            selectedItems.textContent =
                selected.length > 0 ? selected.join(", ") : "Select categories";
        });
    });
});
