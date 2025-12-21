// -------------------------------
// Debounced search + focus after reload
// -------------------------------
let debounceTimer = null;

// Make sure your input has id="search"
const searchInput = document.getElementById('search');

if (searchInput) {

    // Listen for typing with debounce
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            const search = searchInput.value.trim();
            const url = new URL(window.location.href);

            // Set search query and reset page
            url.searchParams.set('search', search);
            url.searchParams.set('page', 1);

            // Set focus flag to restore input focus after reload
            url.searchParams.set('focus', '1');

            // Navigate to new URL
            window.location.href = url.toString();
        }, 500); // 0.5 second debounce
    });

    // On page load, if focus flag is set, restore focus
    window.addEventListener('load', () => {
        const url = new URL(window.location.href);
        if (url.searchParams.get('focus') === '1') {
            searchInput.focus();

            // Move cursor to end of text
            const len = searchInput.value.length;
            searchInput.setSelectionRange(len, len);
        }
    });
}
