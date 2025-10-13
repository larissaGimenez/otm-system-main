<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('company.{companyId}', function ($user, $companyId) {
    // Verifica se o usuário logado tem acesso à empresa do canal solicitado
    return $user->companies()->where('id', $companyId)->exists();
});
