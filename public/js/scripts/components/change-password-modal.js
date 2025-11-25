// Toggle password visibility - HARUS GLOBAL agar bisa diakses dari onclick
window.togglePasswordVisibility = function (inputId, iconId) {
    const $input = $('#' + inputId);
    const $icon = $('#' + iconId);

    if ($input.attr('type') === 'password') {
        $input.attr('type', 'text');
        $icon.attr('data-lucide', 'eye-off');
    } else {
        $input.attr('type', 'password');
        $icon.attr('data-lucide', 'eye');
    }

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
};

// Close change password modal
window.closeChangePasswordModal = function () {
    $('#changePasswordModal').addClass('hidden');
    $('body').css({
        overflow: '',
        position: '',
        width: '',
    });
    $('#changePasswordForm')[0].reset();
    $('#changePasswordNew').attr('type', 'password');
    $('#changePasswordConfirm').attr('type', 'password');
};

// modal password opened
$(document).ready(function () {
    $(document).on('click', '.change-password-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name') || 'User';

        $('#userDropdown').addClass('hidden');
        $('.menu-dropdown').addClass('hidden');

        const newAction = '/e-log-karyawan/karyawan/' + userId + '/password';
        $('#changePasswordForm').attr('action', newAction);
        const verifyAction = $('#changePasswordForm').attr('action');

        $('#changePasswordModalTitle').text('Ubah Password - ' + userName);

        $('#changePasswordForm')[0].reset();
        $('#changePasswordNew').attr('type', 'password');
        $('#changePasswordConfirm').attr('type', 'password');

        $('#changePasswordModal').removeClass('hidden');

        $('body').css({
            overflow: 'hidden',
            position: 'fixed',
            width: '100%',
        });
    });

    $('#changePasswordForm').on('submit', function (e) {
        const form = $(this);
        const currentAction = form.attr('action');

        if (!currentAction || currentAction.trim() === '') {
            e.preventDefault();
            console.error('ERROR: Form action kosong!');
            alert('Terjadi kesalahan: Form action tidak ditemukan. Silakan tutup modal dan coba lagi.');
            return false;
        }

        const userIdMatch = currentAction.match(/\/e-log-karyawan\/karyawan\/(\d+)\/password/);
        if (!userIdMatch) {
            e.preventDefault();
            console.error('ERROR: Form action tidak valid!');
            console.error('Action harus dalam format: /e-log-karyawan/karyawan/{userId}/password');
            console.error('Current action:', currentAction);
            alert('Terjadi kesalahan: Form action tidak valid. Silakan tutup modal dan coba lagi.');
            return false;
        }

        const userIdInAction = userIdMatch[1];
        return true;
    });
});
