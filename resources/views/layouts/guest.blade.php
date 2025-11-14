<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        {{-- MUDANÇA AQUI: Carrega 'guest.scss' e NÃO 'app.scss' --}}
        @vite(['resources/scss/guest.scss', 'resources/js/app.js'])
    </head>
    <body class="bg-light">
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-4">
                        <div class="text-center mb-4">
                            <a href="/">
                                {{-- Ajustei o logo com base no seu print --}}
                                <img src="{{ asset('images/logo_boxfarma.png') }}" alt="Boxfarma" style="height: 50px;" class="mx-auto">
                            </a>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>