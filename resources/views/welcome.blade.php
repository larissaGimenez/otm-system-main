<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Migrador Omie</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="antialiased bg-light d-flex flex-column min-vh-100"> 
    <header class="container py-3">
        {{-- MUDANÇA SUTIL: Removido d-flex e justify-content-end, pois o nav já faz isso --}}
        @if (Route::has('login'))
            <nav class="nav justify-content-end">
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-link text-secondary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link text-secondary">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-link text-secondary">Register</a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    {{-- A classe 'flex-grow-1' faz esta seção ocupar todo o espaço vertical disponível --}}
    <main class="flex-grow-1 d-flex align-items-center justify-content-center">
        {{-- MUDANÇA PRINCIPAL: Removida a margem vertical 'my-5' para deixar o flexbox controlar o espaço --}}
        <div class="px-4 py-3 text-center">
            <img src="{{ asset('images/logo_otm_tech.png') }}" alt="Logo OTM Tech" class="mb-4 mx-auto" style="height: 100px;">
            
            <div class="col-lg-6 mx-auto">
              
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    @auth
                         <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg px-4 me-sm-3">Acessar Painel</a>
                    @else
                         <a href="{{ route('login') }}" type="button" class="btn btn-primary btn-lg px-4 me-sm-3">Fazer Login</a>
                         <a href="{{ route('register') }}" type="button" class="btn btn-outline-secondary btn-lg px-4">Registrar-se</a>
                    @endauth
                </div>
            </div>
        </div>
    </main>

    <footer class="mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} OTM Tech. Todos os direitos reservados.</span>
        </div>
    </footer>
</body>
</html>