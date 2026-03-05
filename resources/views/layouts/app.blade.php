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

        #app {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        #app > nav {
            flex-shrink: 0;
        }

        .wrapper {
            flex: 1;
            display: flex;
            min-height: 0;
            /* overflow: hidden; */
            width: 100%;
        }

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

        #content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
        }

        #content-wrapper > header {
            flex-shrink: 0;
        }

        #content-wrapper > main {
            flex: 1;
            overflow: auto;
            min-height: 0;
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

        @media (max-width: 768px) {
        #sidebar {
            width: 100% !important; 
            position: fixed;
            z-index: 1040; 
            height: 100vh;
            margin-left: 0; 
            transition: margin-left 0.3s ease-in-out;
        }

        .sidebar-toggled #sidebar {
            margin-left: -100% !important;
        }

        body:not(.sidebar-toggled) {
            overflow: hidden;
        }
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
                    <header class="mb-3 mt-4">
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

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        function adjustSidebarForMobile() {
            if (window.innerWidth < 768) {
                // No seu sistema, para ESCONDER, o body PRECISA da classe 'sidebar-toggled'
                document.body.classList.add('sidebar-toggled');

                // Opcional: Fecha também os submenus internos para não ocuparem espaço
                const sidebar = document.getElementById('sidebar');
                if (sidebar) {
                    const openSubmenus = sidebar.querySelectorAll('.collapse.show');
                    openSubmenus.forEach(submenu => submenu.classList.remove('show'));
                    
                    const toggles = sidebar.querySelectorAll('.dropdown-toggle');
                    toggles.forEach(btn => {
                        btn.classList.add('collapsed');
                        btn.setAttribute('aria-expanded', 'false');
                    });
                }
            } else {
                // No Desktop, se você quiser que ele nasça aberto sempre:
                // document.body.classList.remove('sidebar-toggled');
            }
        }

        adjustSidebarForMobile();
        
        // Se girar o celular ou redimensionar a janela
        window.addEventListener('resize', adjustSidebarForMobile);
    });
</script>
</body>
</html>