<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Language" content="id">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HR Ludira') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- JQuery CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Flatpickr (Date & Time Picker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js"></script>

    <!-- Apexchart Cdn -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Icon -->
    <!-- <link rel="icon" href="path/to/your/favicon.ico" type="image/x-icon"> -->
    <link rel="stylesheet" href="{{ asset('css/app.blade.css') }}">

</head>

<body class="bg-white">
    @guest
    <!-- Login/Register - No Sidebar -->
    <div id="app">
        @include('components.navbar')
        @yield('content')
    </div>
    @else
    <!-- Authenticated - With Sidebar -->
    @yield('content')
    @endguest

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="{{ asset('js/scripts/app.js') }}"></script>
    <!-- <script>
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

        $(document).on('click', '#sidebarOverlay', function() {
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

        $(document).ready(function() {
            lucide.createIcons();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#toggleSidebar, #toggleSidebarMobile').on('click', function() {
                toggleSidebar();
            });

            $('#alertContainer > div').each(function() {
                const $alert = $(this);
                setTimeout(function() {
                    $alert.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 5000);
            });

        });
    </script> -->

    <!-- Apexchart Cdn -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</body>

</html>
