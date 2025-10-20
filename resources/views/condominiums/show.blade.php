<x-app-layout>
    @section('sidebar')
        @include('layouts._sidebar-admin')
    @endsection

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="title-main">
                Detalhes da Empresa
            </h2>
            <div>
                <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i> Voltar para a Lista
                </a>
                <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-fill me-2"></i> Editar Empresa
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white p-4 rounded shadow-sm">

        {{-- SEÇÃO DE IDENTIFICAÇÃO PRINCIPAL --}}
        <div class="d-flex align-items-center pb-3 border-bottom mb-4">
            @if ($company->logo_path)
                <img src="{{ Storage::url($company->logo_path) }}" alt="Logo de {{ $company->name }}" class="rounded me-4" style="width: 80px; height: 80px; object-fit: contain;">
            @else
                <div class="rounded me-4 bg-light d-flex justify-content-center align-items-center text-secondary" style="width: 80px; height: 80px;">
                    <i class="bi bi-building fs-1"></i>
                </div>
            @endif
            <div>
                <h1 class="h3 fw-bold mb-0">{{ $company->name }}</h1>
                <p class="text-muted mb-0">{{ $company->razao_social }}</p>
            </div>
        </div>

        {{-- Seções de detalhes organizadas --}}
        <div class="row">
            {{-- Coluna da Esquerda --}}
            <div class="col-md-6">
                <h3 class="title-section">Informações Fiscais</h3>
                <dl class="row mt-3">
                    <dt class="col-sm-5 text-muted">CNPJ</dt>
                    {{-- O Accessor no Model já formata o CNPJ automaticamente --}}
                    <dd class="col-sm-7">{{ $company->cnpj }}</dd>

                    <dt class="col-sm-5 text-muted">Inscrição Estadual</dt>
                    <dd class="col-sm-7">{{ $company->inscricao_estadual ?? 'Não informada' }}</dd>
                </dl>

                <h3 class="title-section mt-4">Contato</h3>
                <dl class="row mt-3">
                    <dt class="col-sm-5 text-muted">E-mail</dt>
                    <dd class="col-sm-7">{{ $company->email ?? 'Não informado' }}</dd>

                    <dt class="col-sm-5 text-muted">Telefone</dt>
                    <dd class="col-sm-7">{{ $company->telefone ?? 'Não informado' }}</dd>
                </dl>
            </div>

            {{-- Coluna da Direita --}}
            <div class="col-md-6">
                <h3 class="title-section">Endereço</h3>
                <dl class="row mt-3">
                    <dt class="col-sm-5 text-muted">CEP</dt>
                     {{-- O Accessor no Model já formata o CEP automaticamente --}}
                    <dd class="col-sm-7">{{ $company->cep }}</dd>

                    <dt class="col-sm-5 text-muted">Logradouro</dt>
                    <dd class="col-sm-7">{{ $company->logradouro }}, {{ $company->numero }}</dd>

                    @if($company->complemento)
                        <dt class="col-sm-5 text-muted">Complemento</dt>
                        <dd class="col-sm-7">{{ $company->complemento }}</dd>
                    @endif

                    <dt class="col-sm-5 text-muted">Bairro</dt>
                    <dd class="col-sm-7">{{ $company->bairro }}</dd>

                    <dt class="col-sm-5 text-muted">Cidade / Estado</dt>
                    <dd class="col-sm-7">{{ $company->cidade }} / {{ $company->estado }}</dd>
                </dl>
            </div>
        </div>

        {{-- SEÇÃO DE HISTÓRICO --}}
        <div class="pt-3 mt-4 border-top">
             <h3 class="title-section">Histórico</h3>
             <dl class="row mt-3">
                <dt class="col-sm-3 text-muted">Data de Cadastro</dt>
                <dd class="col-sm-9">{{ $company->created_at->format('d/m/Y \à\s H:i') }}</dd>
                
                <dt class="col-sm-3 text-muted">Última Atualização</dt>
                <dd class="col-sm-9">{{ $company->updated_at->format('d/m/Y \à\s H:i') }}</dd>
             </dl>
        </div>
        
    </div>
</x-app-layout>