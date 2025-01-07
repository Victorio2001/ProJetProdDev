const tbody = document.querySelector('tbody');
const th = document.querySelectorAll('thead th');
const bookData = document.querySelectorAll('.book-data');

const rows = Array.from(bookData);

// SORT
let sortDirection = {};

th.forEach((col, idx) => {
    sortDirection[idx] = false; // Initialize sort direction for each column
    col.addEventListener('click', () => {
        // Toggle sort direction
        sortDirection[idx] = !sortDirection[idx];

        const filteredRows = rows.filter(item => item.style.display !== 'none');

        filteredRows.sort((a, b) => {
            // sort + localeCompare with innerHTML to sort special characters
            return sortDirection[idx] ? a.cells[idx].innerHTML.localeCompare(b.cells[idx].innerHTML) : b.cells[idx].innerHTML.localeCompare(a.cells[idx].innerHTML);
        }).forEach((row) => {
            // Corrected insertion of sorted rows
            tbody.appendChild(row);
        });
    });
});
