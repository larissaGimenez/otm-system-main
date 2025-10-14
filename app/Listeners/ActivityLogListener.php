<?php

namespace App\Listeners;

use App\Models\ActivityLog; // ✅ ESSENCIAL: Adicione este 'use'
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class ActivityLogListener
{
    // Adicionamos a verificação 'if ($model instanceof ActivityLog)' em todos os métodos 'handle'

    public function handleModelCreated(string $event, array $data): void
    {
        try {
            $model = $data[0];
            if ($model instanceof ActivityLog) return; // ✅ A CORREÇÃO: Se for um log, pare aqui.
            $this->logActivity($model, 'criado');
        } catch (\Exception $e) {
            Log::error('Falha no listener handleModelCreated: ' . $e->getMessage());
        }
    }

    public function handleModelUpdated(string $event, array $data): void
    {
        try {
            $model = $data[0];
            if ($model instanceof ActivityLog) return; // ✅ A CORREÇÃO
            $this->logActivity($model, 'atualizado');
        } catch (\Exception $e) {
            Log::error('Falha no listener handleModelUpdated: ' . $e->getMessage());
        }
    }

    public function handleModelDeleted(string $event, array $data): void
    {
        try {
            $model = $data[0];
            if ($model instanceof ActivityLog) return; // ✅ A CORREÇÃO
            $this->logActivity($model, 'excluído');
        } catch (\Exception $e) {
            Log::error('Falha no listener handleModelDeleted: ' . $e->getMessage());
        }
    }

    public function handlePivotAttached(string $event, array $data): void
    {
        try {
            $parentModel = $data['parent'];
            if ($parentModel instanceof ActivityLog) return; // ✅ A CORREÇÃO

            $relatedModelClass = $parentModel->{$data['relation']}()->getRelated();
            $relatedModels = $relatedModelClass::find($data['related']);
            
            if ($relatedModels->isEmpty()) return;

            $relatedNames = $relatedModels->pluck('name')->implode(', ');
            $relationName = class_basename($relatedModelClass);

            $this->logActivity($parentModel, "foi associado a {$relationName}(s): {$relatedNames}");
        } catch (\Exception $e) {
            Log::error('Falha no listener handlePivotAttached: ' . $e->getMessage());
        }
    }

    public function handlePivotDetached(string $event, array $data): void
    {
        try {
            $parentModel = $data['parent'];
            if ($parentModel instanceof ActivityLog) return; // ✅ A CORREÇÃO

            $relatedModelClass = $parentModel->{$data['relation']}()->getRelated();
            $relatedModels = $relatedModelClass::find($data['related']);

            if ($relatedModels->isEmpty()) return;

            $relatedNames = $relatedModels->pluck('name')->implode(', ');
            $relationName = class_basename($relatedModelClass);

            $this->logActivity($parentModel, "foi desassociado de {$relationName}(s): {$relatedNames}");
        } catch (\Exception $e) {
            Log::error('Falha no listener handlePivotDetached: ' . $e->getMessage());
        }
    }

    private function logActivity(Model $model, string $description): void
    {
        $model->activityLogs()->create([
            'description' => $description,
            'causer_id' => Auth::id(),
            'causer_name' => Auth::user() ? Auth::user()->name : 'Sistema',
        ]);
    }

    public function subscribe($events): void
    {
        $events->listen('eloquent.created:*', [self::class, 'handleModelCreated']);
        $events->listen('eloquent.updated:*', [self::class, 'handleModelUpdated']);
        $events->listen('eloquent.deleted:*', [self::class, 'handleModelDeleted']);
        $events->listen('eloquent.pivotAttached:*', [self::class, 'handlePivotAttached']);
        $events->listen('eloquent.pivotDetached:*', [self::class, 'handlePivotDetached']);
    }
}