<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'OTM Tech') }}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 280px;
        }
        body {
            overflow-x: hidden;
        }
        .wrapper {
            display: flex;
            width: 100%;
            min-height: calc(100vh - 56px);
            align-items: stretch;
        }
        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            transition: margin-left 0.35s ease-in-out;
            margin-left: 0;
        }
        .sidebar-toggled #sidebar {
            margin-left: calc(-1 * var(--sidebar-width));
        }
        #content-wrapper {
            width: 100%;
            transition: width 0.35s ease-in-out;
        }
        #sidebar .nav-link:not(.active):hover {
            background-color: #e9ecef;
        }
        #sidebar .dropdown-toggle::after {
            transition: transform 0.3s ease-in-out;
        }
        #sidebar .dropdown-toggle:not(.collapsed)::after {
            transform: rotate(180deg);
        }
        #sidebar .nav-link-sub {
            font-size: 0.800rem; 
            color: #6c757d; 
            padding-top: 0.35rem;
            padding-bottom: 0.35rem;
            padding-left: 3rem; 
        }

        #sidebar .nav-link-sub:hover,
        #sidebar .nav-link-sub.active {
            color: #0d6efd; 
            font-weight: bold;
        }
    </style>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-light">
    <div>
        @include('layouts.navigation')

        <div class="wrapper">
            @include('layouts.sidebar')

            <main id="content-wrapper" class="p-4">
                @if (isset($header))
                    <header class="mb-4">
                        <div>{{ $header }}</div>
                    </header>
                @endif
                
                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.body.classList.toggle('sidebar-toggled');
                });
            }

            const sidebarCollapses = document.querySelectorAll('#sidebar .collapse');
            sidebarCollapses.forEach(function (collapseEl) {
                collapseEl.addEventListener('show.bs.collapse', function () {
                    const openCollapses = document.querySelectorAll('#sidebar .collapse.show');
                    openCollapses.forEach(function (openCollapse) {
                        const bsCollapse = bootstrap.Collapse.getInstance(openCollapse);
                        if (bsCollapse) {
                            bsCollapse.hide();
                        }
                    });
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>