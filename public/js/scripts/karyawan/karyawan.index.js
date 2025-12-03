function openEmployeeDetail(employeeId, name, email, role, department, joinedDate, initials, userId, departemenId, unitId, passwordChanged, departemenNama, unitNama, isRegistered, userRole) {
    const $modal = $('#employeeDetailModal');
    const $panel = $('#slideoutPanel');

    if (!$modal.length || !$panel.length) {
        console.error('Modal elements not found!');
        alert('Modal tidak ditemukan. Silakan refresh halaman.');
        return;
    }

    $('#employeeName').text(name);
    $('#employeeEmail').text(email || '-');
    $('#employeeDepartment').text(departemenNama || 'Belum Ditentukan');
    $('#employeeId').text(employeeId);
    $('#employeeAvatar').text(initials);
    $('#employeeUnit').text(unitNama || 'Belum Ditentukan');

    $('#employeeNameInput').val(name);
    $('#userId').val(userId || '');

    if (employeeId) {
        const formAction = '/e-log-karyawan/karyawan/' + employeeId + '/update';
        $('#employeeForm').attr('action', formAction);
        $('#employeeIdForForm').val(employeeId);
    }

    if (departemenId) {
        $('#employeeDepartemenSelect').val(departemenId);
        loadUnitsForDepartemen(departemenId, unitId);
    } else {
        $('#employeeDepartemenSelect').val('');
        const $unitSelect = $('#employeeUnitSelect');
        $unitSelect.find('option').show();
        $unitSelect.val('');
    }

    const isPasswordChanged = passwordChanged === true || passwordChanged === 'true';
    $('#employeePasswordChanged').prop('checked', isPasswordChanged);
    if (isPasswordChanged) {
        $('#passwordChangedHint').text('Password sudah diubah oleh user');
    } else {
        $('#passwordChangedHint').text('Password masih default (12345)');
    }

    $('#employeePassword').val('');
    $('#employeePasswordConfirmation').val('');

    $('#employeePassword').attr('type', 'password');
    $('#employeePasswordConfirmation').attr('type', 'password');

    $('#employeeForm').show();

    const $statusBadge = $('#employeeStatus');
    const $statusDot = $('#statusDot');
    const $statusText = $('#statusText');

    if (isRegistered === true || isRegistered === 'true') {
        $statusBadge.attr('class', 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700');
        $statusDot.attr('class', 'w-1.5 h-1.5 bg-green-500 rounded-full');
        $statusText.text('Terdaftar');
    } else {
        $statusBadge.attr('class', 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700');
        $statusDot.attr('class', 'w-1.5 h-1.5 bg-red-500 rounded-full');
        $statusText.text('Belum Terdaftar');
    }

    $modal.removeClass('hidden');
    setTimeout(function () {
        $panel.removeClass('translate-x-full');
    }, 10);

    $('body').css({
        overflow: 'hidden',
        position: 'fixed',
        width: '100%',
    });
}

function loadUnitsForDepartemen(departemenId, selectedUnitId) {
    const $unitSelect = $('#employeeUnitSelect');

    $unitSelect.find('option').each(function () {
        if ($(this).val() === '') {
            return;
        }
        if ($(this).data('departemen') == departemenId) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });

    if (selectedUnitId) {
        $unitSelect.val(selectedUnitId);
    } else {
        $unitSelect.val('');
    }
}

function closeEmployeeDetail() {
    const $modal = $('#employeeDetailModal');
    const $panel = $('#slideoutPanel');

    $panel.addClass('translate-x-full');

    setTimeout(function () {
        $modal.addClass('hidden');
        $('body').css({
            overflow: '',
            position: '',
            width: '',
        });
    }, 300);
}

function togglePasswordVisibility(inputId, iconId) {
    const $input = $('#' + inputId);
    const $icon = $('#' + iconId);

    if ($input.attr('type') === 'password') {
        $input.attr('type', 'text');
        $icon.attr('data-lucide', 'eye-off');
    } else {
        $input.attr('type', 'password');
        $icon.attr('data-lucide', 'eye');
    }
}

function saveEmployeeDetail() {
    const userId = $('#userId').val();

    if (!userId || userId === '') {
        alert('User belum terdaftar. Silakan aktifkan terlebih dahulu melalui tombol aktivasi.');
        return;
    }

    const $form = $('#employeeForm');
    const formData = $form.serializeArray();

    const data = {};
    $.each(formData, function (i, field) {
        if ((field.name === 'password' || field.name === 'password_confirmation') && !field.value) {
            return;
        }
        data[field.name] = field.value;
    });

    if (data.password) {
        if (!data.password_confirmation) {
            alert('Konfirmasi password wajib diisi!');
            $('#employeePasswordConfirmation').focus();
            return;
        }
        if (data.password !== data.password_confirmation) {
            alert('Password dan konfirmasi password tidak cocok!');
            $('#employeePasswordConfirmation').focus();
            return;
        }
        if (data.password.length < 6) {
            alert('Password minimal 6 karakter!');
            $('#employeePassword').focus();
            return;
        }
    }

    if (!data.name) {
        alert('Nama wajib diisi!');
        $('#employeeNameInput').focus();
        return;
    }

    const $saveBtn = $('button[onclick="saveEmployeeDetail()"]');
    const originalText = $saveBtn.html();
    $saveBtn.prop('disabled', true).html('<span class="flex items-center"><i data-lucide="loader-2" class="mr-2 h-4 w-4 animate-spin text-white"></i>Menyimpan...</span>');

    $.ajax({
        url: '/karyawan/' + userId,
        method: 'POST',
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
        },
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'PUT',
            ...data,
        },
        success: function (response) {
            $saveBtn.prop('disabled', false).html(originalText);

            closeEmployeeDetail();

            if (response && response.message) {
                alert(response.message);
            } else if (data.password) {
                alert('Password berhasil diubah!');
            } else {
                alert('Data berhasil diperbarui!');
            }

            location.reload();
        },
        error: function (xhr, status, error) {
            $saveBtn.prop('disabled', false).html(originalText);
            console.log;

            let errorMessage = 'Error menyimpan data. Silakan coba lagi.';
            console.log();
            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join('\n');
                }
            } else if (xhr.responseText) {
                try {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(xhr.responseText, 'text/html');
                    const errorElement = doc.querySelector('.error, .alert-danger, [role="alert"]');
                    if (errorElement) {
                        errorMessage = errorElement.textContent.trim();
                    }
                } catch (e) {}
            }

            console.error('Update error:', {
                status: xhr.status,
                statusText: xhr.statusText,
                response: xhr.responseJSON || xhr.responseText,
                error: error,
            });

            alert(errorMessage);
        },
    });
}

$(document).on('click', '.menu-toggle-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();

    const $button = $(this);
    const $parent = $button.closest('.relative.inline-block');
    const $dropdown = $parent.find('.menu-dropdown').first();
    const $allDropdowns = $('.menu-dropdown');

    $allDropdowns.not($dropdown).addClass('hidden');

    if ($dropdown.length) {
        const isHidden = $dropdown.hasClass('hidden');

        if (isHidden) {
            const buttonOffset = $button.offset();
            const buttonWidth = $button.outerWidth();
            const buttonHeight = $button.outerHeight();

            const rightPosition = $(window).width() - (buttonOffset.left + buttonWidth);
            const topPosition = buttonOffset.top + buttonHeight + 8;

            $dropdown.css({
                position: 'fixed',
                top: topPosition + 'px',
                right: rightPosition + 'px',
                left: 'auto',
                'z-index': '9999',
            });
        }

        $dropdown.toggleClass('hidden');
    } else {
        console.warn('Dropdown not found for menu toggle button');
    }
});

$(document).on('click', function (e) {
    if (!$(e.target).closest('.menu-toggle-btn, .menu-dropdown').length) {
        $('.menu-dropdown').addClass('hidden');
    }
});

// Open change role modal
$(document).on('click', '.edit-role-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();

    const userId = $(this).data('user-id');
    const userName = $(this).data('user-name');
    const userRole = $(this).data('user-role') || '';

    $('.menu-dropdown').addClass('hidden');

    $('#changeRoleUserId').val(userId);
    $('#changeRoleModalTitle').text('Ubah Role - ' + userName);

    const formAction = '/e-log-karyawan/karyawan/' + userId + '/role';
    $('#changeRoleForm').attr('action', formAction);

    $('#oldRole').val(userRole);
    $('#newRole').val(userRole);

    const $modal = $('#changeRoleModal');
    if ($modal.length) {
        $modal.removeClass('hidden');
    }

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    $('body').css({
        overflow: 'hidden',
        position: 'fixed',
        width: '100%',
    });
});

// Close change role modal
window.closeChangeRoleModal = function () {
    const $modal = $('#changeRoleModal');
    if ($modal.length) {
        $modal.addClass('hidden');
    }

    $('body').css({
        overflow: '',
        position: '',
        width: '',
    });

    if ($('#changeRoleForm').length) {
        $('#changeRoleForm')[0].reset();
    }
};

let searchTimeout;

function loadData() {
    const search = $('#searchInput').val();
    const filter = $('#filterDepartemen').val();

    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (filter && filter !== 'Semua Departemen') params.append('filter_departemen', filter);

    const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');

    $('#karyawanTableBody').html('<tr><td colspan="100%" class="text-center py-4">Loading...</td></tr>');

    $.get(url, function (response) {
        const $html = $(response);
        $('#karyawanTableBody').html($html.find('#karyawanTableBody').html());
        $('.pagination').first().replaceWith($html.find('.pagination').first());

        // Close all dropdowns after reload
        $('.menu-dropdown').addClass('hidden');

        // Re-initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
}

$(document).ready(function () {
    $(document).on('keydown', function (event) {
        if (event.key === 'Escape') {
            closeEmployeeDetail();
        }
    });

    $(document).on('click', '#employeeDetailModal > .fixed.inset-0.bg-black\\/50', function () {
        closeEmployeeDetail();
    });

    $('#filterDepartemen').on('change', function () {
        loadData();
    });

    $('#searchInput').on('keyup', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadData, 500);
    });

    $('#employeeDepartemenSelect').on('change', function () {
        const departemenId = $(this).val();
        loadUnitsForDepartemen(departemenId, null);
    });

    $(document).on('click', '.open-employee-detail-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const $btn = $(this);
        const employeeId = $btn.data('employee-id');
        const name = $btn.data('name');
        const email = $btn.data('email');
        const role = $btn.data('role');
        const department = $btn.data('department');
        const joinedDate = $btn.data('joined-date');
        const initials = $btn.data('initials');
        const userId = $btn.data('user-id') || null;
        const departemenId = $btn.data('departemen-id') || null;
        const departemenNama = $btn.data('departemen-nama') || 'Belum Ditentukan';
        const unitId = $btn.data('unit-id') || null;
        const unitNama = $btn.data('unit-nama') || 'Belum Ditentukan';
        const passwordChanged = $btn.data('password-changed') === 'true' || $btn.data('password-changed') === true;
        const isRegistered = $btn.data('is-registered') === 'true' || $btn.data('is-registered') === true;
        const userRole = $btn.data('user-role') || null;

        openEmployeeDetail(employeeId, name, email, role, department, joinedDate, initials, userId, departemenId, unitId, passwordChanged, departemenNama, unitNama, isRegistered, userRole);
    });

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const search = $('#searchInput').val();
            const filter = $('#filterDepartemen').val();
            const urlObj = new URL(url, window.location.origin);
            if (search) urlObj.searchParams.set('search', search);
            else urlObj.searchParams.delete('search');
            if (filter && filter !== 'Semua Departemen') urlObj.searchParams.set('filter_departemen', filter);
            else urlObj.searchParams.delete('filter_departemen');
            window.location.href = urlObj.toString();
        }
    });
});
