$(document).ready(function () {
    const $selectAllCheckbox = $('#selectAll');
    const $itemCheckboxes = $('.item-checkbox');
    const $bulkActionBar = $('#bulkActionBar');
    const $selectedCountSpan = $('#selectedCount');
    const $bulkApproveBtn = $('#bulkApproveBtn');
    const $bulkRejectBtn = $('#bulkRejectBtn');
    const $cancelBulkActionBtn = $('#cancelBulkAction');
    const $bulkRejectForm = $('#bulkRejectForm');

    if ($selectAllCheckbox.length) {
        $selectAllCheckbox.on('change', function () {
            $itemCheckboxes.prop('checked', $(this).prop('checked'));
            updateBulkActionBar();
        });
    }

    $itemCheckboxes.on('change', function () {
        updateBulkActionBar();
        if ($selectAllCheckbox.length) {
            $selectAllCheckbox.prop('checked', $itemCheckboxes.length === $itemCheckboxes.filter(':checked').length);
        }
    });

    function updateBulkActionBar() {
        const selectedCount = $itemCheckboxes.filter(':checked').length;

        if (selectedCount > 0) {
            $bulkActionBar.removeClass('hidden');
            $selectedCountSpan.text(selectedCount);
        } else {
            $bulkActionBar.addClass('hidden');
        }
    }

    $bulkApproveBtn.on('click', function () {
        const selected = $itemCheckboxes.filter(':checked');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        if (selected.length === 0) {
            alert('Pilih minimal 1 item untuk divalidasi');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menyetujui ' + selected.length + ' log aktivitas?')) {
            const $form = $('<form>', {
                method: 'POST',
                action: window.bulkApproveRoute,
            });

            $form.append(
                $('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: csrfToken,
                })
            );

            selected.each(function () {
                $form.append(
                    $('<input>', {
                        type: 'hidden',
                        name: 'selected_items[]',
                        value: $(this).val(),
                    })
                );
            });

            $('body').append($form);
            $form.submit();
        }
    });

    $bulkRejectBtn.on('click', function () {
        const selected = $itemCheckboxes.filter(':checked');

        if (selected.length === 0) {
            alert('Pilih minimal 1 item untuk ditolak');
            return;
        }

        $bulkRejectForm.find('input[name="selected_items[]"]').remove();

        selected.each(function () {
            $bulkRejectForm.append(
                $('<input>', {
                    type: 'hidden',
                    name: 'selected_items[]',
                    value: $(this).val(),
                })
            );
        });

        openBulkRejectModal();
    });

    $cancelBulkActionBtn.on('click', function () {
        $itemCheckboxes.prop('checked', false);
        $selectAllCheckbox.prop('checked', false);
        updateBulkActionBar();
    });

    function openBulkRejectModal() {
        $('#bulkRejectModal').removeClass('hidden');
    }

    window.closeBulkRejectModal = function () {
        $('#bulkRejectModal').addClass('hidden');
    };
});
