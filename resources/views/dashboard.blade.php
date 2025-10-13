<x-app-layout>
    
    @section('sidebar')
        @include('layouts._sidebar-app')
    @endsection

    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title">Bem-vindo ao seu painel!</h5>
            <p class="card-text text-muted">
                {{ __("You're logged in!") }} Utilize o menu lateral para iniciar o processo de migração.
            </p>
        </div>
    </div>

</x-app-layout>