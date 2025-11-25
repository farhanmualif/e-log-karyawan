$(document).ready(function () {
    lucide.createIcons();

    function applyFilters() {
        const searchValue = $('#searchInput').val().toLowerCase();
        // Filter berdasarkan search text
        $('#departemenTableBody tr').each(function () {
            let showRow = true;
            if (searchValue) {
                const rowText = $(this).text().toLowerCase();
                if (rowText.indexOf(searchValue) === -1) {
                    showRow = false;
                }
            }
            if (showRow) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $('#searchInput').on('keyup', function () {
        applyFilters();
    });
});
