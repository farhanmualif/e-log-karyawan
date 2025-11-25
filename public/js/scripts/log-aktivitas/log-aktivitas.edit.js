$(document).ready(function () {
    $('#waktu_awal, #waktu_akhir').attr('lang', 'id');
    $('#waktu_awal').on('change', function () {
        const value = $(this).val();
        if (value) {
            const [hours, minutes] = value.split(':');
            if (parseInt(hours) >= 24) {
                $(this).val('23:59');
            }
        }
    });
});
