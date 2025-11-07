<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'OTM Tech') }}</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">

    <style>
        /* CRÍTICO: HTML e BODY devem ter 100% de altura */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* Container principal do app */
        #app {
            height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Navigation (se houver) */
        #app > nav {
            flex-shrink: 0;
        }

        /* Wrapper da aplicação */
        .wrapper {
            flex: 1;
            display: flex;
            min-height: 0;
            /* overflow: hidden; */
            width: 100%;
        }

        /* Sidebar */
        #sidebar {
            width: 280px;
            flex-shrink: 0;
            overflow-y: auto;
            overflow-x: hidden;
            transition: margin-left 0.35s ease-in-out;
        }

        .sidebar-toggled #sidebar {
            margin-left: -280px;
        }

        /* Content Wrapper */
        #content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
        }

        /* Header */
        #content-wrapper > header {
            flex-shrink: 0;
        }

        /* Main - Área que pode rolar */
        #content-wrapper > main {
            flex: 1;
            overflow: auto;
            min-height: 0;
        }

        /* Estilos da Sidebar */
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
@livewireStyles

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="h-100">
    <div id="app" class="h-100">
        @include('layouts.navigation')

        <div class="wrapper">
            @include('layouts.sidebar')

            <div id="content-wrapper">
                @if (isset($header))
                    <header class="mb-3">
                        {{ $header }}
                    </header>
                @endif
                
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

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
@livewireScripts
    @stack('scripts')
</body>
</html>