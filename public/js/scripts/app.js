function toggleListSection() {
    const $listSection = $('#listSection');
    const $chevron = $('#listChevron');

    if ($listSection.length) {
        if ($listSection.hasClass('hidden')) {
            $listSection.removeClass('hidden');
            if ($chevron.length) $chevron.css('transform', 'rotate(0deg)');
        } else {
            $listSection.addClass('hidden');
            if ($chevron.length) $chevron.css('transform', 'rotate(-90deg)');
        }
    }
}

function toggleSidebar() {
    const $sidebar = $('#sidebar');
    const $mainContent = $('#mainContent');
    const $overlay = $('#sidebarOverlay');
    const isMobile = window.innerWidth < 768;

    if ($sidebar.length && $mainContent.length) {
        if (isMobile) {
            if ($sidebar.hasClass('-translate-x-full')) {
                $sidebar.removeClass('-translate-x-full');
                if ($overlay.length) {
                    $overlay.removeClass('hidden');
                }
            } else {
                $sidebar.addClass('-translate-x-full');
                if ($overlay.length) {
                    $overlay.addClass('hidden');
                }
            }
        } else {
            if ($mainContent.hasClass('sidebar-visible')) {
                $sidebar.css('transform', 'translateX(-100%)');
                $mainContent.removeClass('sidebar-visible');
                $mainContent.css('margin-left', '0');
            } else {
                $sidebar.css('transform', '');
                $mainContent.css('margin-left', '');
                $mainContent.addClass('sidebar-visible');
            }
        }
        lucide.createIcons();
    }
}

$(document).on('click', '#sidebarOverlay', function () {
    const $sidebar = $('#sidebar');
    const $overlay = $('#sidebarOverlay');
    $sidebar.addClass('-translate-x-full');
    $overlay.addClass('hidden');
});

function toggleDataMaster() {
    const $dataMasterSection = $('#dataMasterSection');
    const $chevron = $('#dataMasterChevron');

    if ($dataMasterSection.length) {
        if ($dataMasterSection.hasClass('hidden')) {
            $dataMasterSection.removeClass('hidden');
            if ($chevron.length) $chevron.css('transform', 'rotate(0deg)');
        } else {
            $dataMasterSection.addClass('hidden');
            if ($chevron.length) $chevron.css('transform', 'rotate(-90deg)');
        }
        lucide.createIcons();
    }
}

$(document).ready(function () {
    lucide.createIcons();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    });

    $('#toggleSidebar, #toggleSidebarMobile').on('click', function () {
        toggleSidebar();
    });

    $('#alertContainer > div').each(function () {
        const $alert = $(this);
        setTimeout(function () {
            $alert.fadeOut(300, function () {
                $(this).remove();
            });
        }, 5000);
    });
});
