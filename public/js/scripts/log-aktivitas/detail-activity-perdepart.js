$(document).ready(function () {
    // Accordion toggle functionality
    $('.accordion-toggle-btn, .accordion-header').on('click', function (e) {
        e.stopPropagation();
        const userId = $(this).data('user-id');
        const $accordionContent = $(`#accordion-${userId}`);
        const $icon = $(`.accordion-icon[data-user-id="${userId}"]`);

        $accordionContent.toggleClass('hidden');

        if ($accordionContent.hasClass('hidden')) {
            $icon.attr('data-lucide', 'chevron-down');
        } else {
            $icon.attr('data-lucide', 'chevron-up');
        }

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    // Handle pagination clicks untuk activities di dalam accordion
    $(document).on('click', '.activities-pagination-link', function (e) {
        e.preventDefault();

        const $link = $(this);
        const url = $link.attr('href');

        if (!url || url === '#') {
            return;
        }

        // Ambil user_id dari container terdekat
        const $container = $link.closest('[id^="activities-container-"]');
        const containerId = $container.attr('id');
        const userId = containerId.replace('activities-container-', '');
        const departemenId = $container.data('departemen-id');

        // Extract page number dari URL
        const urlObj = new URL(url, window.location.origin);
        const pageParam = urlObj.searchParams.get(`activity_page_${userId}`);
        const page = pageParam || 1;

        // Show loading state
        const $tbody = $(`#activities-tbody-${userId}`);
        const $pagination = $(`#activities-pagination-${userId}`);
        $tbody.html('<tr><td colspan="5" class="px-4 py-8 text-center"><div class="flex items-center justify-center"><i data-lucide="loader-2" class="w-5 h-5 animate-spin text-gray-400"></i><span class="ml-2 text-sm text-gray-600">Memuat data...</span></div></td></tr>');
        $pagination.html('');

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Build API URL
        const apiUrl = window.routeGetActivitiesByUser.replace(':user_id', userId) + `?activity_page_${userId}=${page}&activity_per_page=5`;

        // Fetch data dari API
        fetch(apiUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'application/json',
            },
        })
            .then((response) => response.json())
            .then((result) => {
                if (result.status && result.data) {
                    renderActivitiesTable(userId, result.data);
                } else {
                    showError(userId, result.message || 'Gagal memuat data activities');
                }
            })
            .catch((error) => {
                console.error('Error loading activities:', error);
                showError(userId, 'Terjadi kesalahan saat memuat data');
            });
    });

    /**
     * Render activities table dari data pagination
     */
    function renderActivitiesTable(userId, paginationData) {
        const $tbody = $(`#activities-tbody-${userId}`);
        const $pagination = $(`#activities-pagination-${userId}`);

        /* -----------------------------
       RENDER TABLE ROWS
    ----------------------------- */
        if (paginationData.data && paginationData.data.length > 0) {
            let rowsHtml = '';

            paginationData.data.forEach((activity) => {
                const tanggal = formatDate(activity.tanggal);
                const waktuAwal = formatTime(activity.waktu_awal);
                const waktuAkhir = formatTime(activity.waktu_akhir);
                const aktivitas = truncateText(activity.aktivitas, 100);

                let statusBadge = '';
                if (activity.status === 'menunggu') {
                    statusBadge = `<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>`;
                } else if (activity.status === 'tervalidasi') {
                    statusBadge = `<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">Tervalidasi</span>`;
                } else {
                    statusBadge = `<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>`;
                }

                let validasiHtml = `<span class="text-gray-400">-</span>`;
                if (activity.validated_by && activity.validated_at) {
                    const validatedAt = formatDateTime(activity.validated_at);
                    let catatanHtml = '';

                    if (activity.catatan_validasi) {
                        const catatan = truncateText(activity.catatan_validasi, 50);
                        catatanHtml = `<div class="text-xs text-gray-600 bg-gray-50 rounded p-1 mt-1"><strong>Catatan:</strong> ${catatan}</div>`;
                    }

                    validasiHtml = `
                    <div class="space-y-1">
                        <div class="flex items-center gap-1">
                            <i data-lucide="user-check" class="w-3 h-3"></i>
                            <span>${activity.nama_validator || 'N/A'}</span>
                        </div>
                        <div class="text-xs text-gray-500">${validatedAt}</div>
                        ${catatanHtml}
                    </div>
                `;
                }

                rowsHtml += `
                <tr class="hover:bg-gray-50">
                    <td class="px-2 py-2 text-xs sm:text-sm text-gray-900">${tanggal}</td>
                    <td class="px-2 py-2 text-xs sm:text-sm text-gray-900">
                        <div class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-3 h-3 text-teal-500"></i>
                            <span>${waktuAwal} - ${waktuAkhir}</span>
                        </div>
                    </td>
                    <td class="px-2 py-2 text-xs sm:text-sm text-gray-900">
                        <div class="max-w-md"><p class="line-clamp-2">${aktivitas}</p></div>
                    </td>
                    <td class="px-2 py-2 whitespace-nowrap">${statusBadge}</td>
                    <td class="px-2 py-2 text-xs sm:text-sm text-gray-600">${validasiHtml}</td>
                </tr>
            `;
            });

            $tbody.html(rowsHtml);
        } else {
            $tbody.html(`
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">
                    Tidak ada data aktivitas
                </td>
            </tr>
        `);
        }

        /* -----------------------------
       RENDER PAGINATION
    ----------------------------- */
        if (paginationData.last_page > 1 && paginationData.links && paginationData.links.length > 0) {
            let paginationHtml = `
            <div class="mt-3 pt-3 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                    <div class="text-xs text-gray-600">
                        Menampilkan ${paginationData.from || 0} - ${paginationData.to || 0} dari ${paginationData.total || 0} aktivitas
                    </div>
                    <div class="flex items-center justify-center">
                        <nav class="flex items-center space-x-1">
        `;

            paginationData.links.forEach((link) => {
                const isActive = link.active ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-50';
                const disabled = link.url === null ? 'pointer-events-none opacity-50' : '';

                if (link.url) {
                    paginationHtml += `
                    <a href="${link.url}"
                       class="activities-pagination-link px-3 py-1 text-xs border border-gray-300 rounded ${isActive} ${disabled}"
                       data-user-id="${userId}">
                        ${link.label}
                    </a>
                `;
                } else {
                    paginationHtml += `
                    <span class="px-3 py-1 text-xs border border-gray-300 rounded bg-gray-100 text-gray-400 pointer-events-none opacity-50">
                        ${link.label}
                    </span>
                `;
                }
            });

            paginationHtml += `
                        </nav>
                    </div>
                </div>
            </div>
        `;

            $pagination.html(paginationHtml);
        } else {
            // ❗ Jangan hapus kontainer pagination, biarkan kosong
            $pagination.html('');
        }

        /* -----------------------------
       RELOAD ICONS
    ----------------------------- */
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    /**
     * Show error message
     */
    function showError(userId, message) {
        const $tbody = $(`#activities-tbody-${userId}`);
        $tbody.html(`
            <tr>
                <td colspan="5" class="px-4 py-8 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <i data-lucide="alert-circle" class="w-8 h-8 text-red-400"></i>
                        <span class="text-sm text-red-600">${message}</span>
                    </div>
                </td>
            </tr>
        `);
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    /**
     * Format date to d/m/Y
     */
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    /**
     * Format time to H:i
     */
    function formatTime(timeString) {
        if (!timeString) return '-';
        const time = new Date(`2000-01-01T${timeString}`);
        const hours = String(time.getHours()).padStart(2, '0');
        const minutes = String(time.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes}`;
    }

    /**
     * Format datetime to d/m/Y H:i
     */
    function formatDateTime(dateTimeString) {
        if (!dateTimeString) return '-';
        const date = new Date(dateTimeString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${day}/${month}/${year} ${hours}:${minutes}`;
    }

    /**
     * Truncate text
     */
    function truncateText(text, maxLength) {
        if (!text) return '';
        if (text.length <= maxLength) return escapeHtml(text);
        return escapeHtml(text.substring(0, maxLength)) + '...';
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        };
        return text.replace(/[&<>"']/g, (m) => map[m]);
    }
});
