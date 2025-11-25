$(document).ready(function () {
    function applyFilters() {
        const filterValue = $('#filterDepartemen').val();
        const searchValue = $('#searchInput').val().toLowerCase();

        $('#unitTableBody tr').each(function () {
            let showRow = true;
            // Filter berdasarkan departemen (kolom index 2)
            if (filterValue && filterValue !== '') {
                const departemen = $(this).find('td:eq(2)').text().trim();
                if (departemen !== filterValue) {
                    showRow = false;
                }
            }
            if (showRow && searchValue) {
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

    $('#filterDepartemen').on('change', function () {
        applyFilters();
    });

    $('#searchInput').on('keyup', function () {
        applyFilters();
    });
});
