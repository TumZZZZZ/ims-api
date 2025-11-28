const recordNotFoundText = window.translations.recordNotFound;

const searchInput = document.getElementById("search");
const tableBody = document.getElementById("table-body");
const rows = Array.from(tableBody.getElementsByClassName("table-body-tr"));

// Create "No records found" row
let noRecordRow = document.getElementById("no-record");
if (!noRecordRow) {
    noRecordRow = document.createElement("tr");
    noRecordRow.id = "no-record";
    noRecordRow.innerHTML = `<td colspan="6" style="text-align:center; padding:15px;">${recordNotFoundText}</td>`;
    noRecordRow.style.display = "none";
    tableBody.appendChild(noRecordRow);
}

searchInput.addEventListener("input", function () {
    const query = this.value.toLowerCase();
    let matchCount = 0;

    rows.forEach((row) => {
        const cells = Array.from(row.getElementsByTagName("td"));
        let found = false;

        cells.forEach((cell, index) => {
            if (index === 0 || index === cells.length - 1) return; // skip first and last column if needed
            const text = cell.textContent;

            if (query !== "" && text.toLowerCase().includes(query)) {
                found = true;
                const regex = new RegExp(`(${query})`, "gi");
                cell.innerHTML = text.replace(regex, `<span style="background: yellow;">$1</span>`);
            } else {
                cell.innerHTML = cell.textContent; // reset highlight
                if (query === "") found = true;
            }
        });

        row.style.display = found ? "" : "none";
        if (found) matchCount++;
    });

    noRecordRow.style.display = matchCount === 0 ? "" : "none";

    // Optional: reset pagination to first page after search
    if (typeof resetPagination === "function") resetPagination();
});
