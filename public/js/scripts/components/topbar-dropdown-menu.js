function toggleUserMenu(event) {
    event.stopPropagation();
    const $dropdown = $('#userDropdown');
    $dropdown.toggleClass('hidden');
}

$(document).on('click', function (event) {
    $('#userDropdown').addClass('hidden');
});

$(document).ready(function () {
    $(document).on('click', '.change-password-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name') || 'User';

        $('#userDropdown').addClass('hidden');
        $('.menu-dropdown').addClass('hidden');

        $('#changePasswordUserId').val(userId);
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
});
