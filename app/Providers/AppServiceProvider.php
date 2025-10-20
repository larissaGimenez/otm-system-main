<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

  
    public function boot(): void
    {
        Blueprint::macro('uuidPrimary', function (string $name = 'id') {
            return $this->uuid($name)->primary();
        });

        /**
         * Cria relacionamento belongsTo com UUID.
         * Exemplo: $table->belongsToUuid('user'); → foreignUuid('user_id')->constrained('users')->cascadeOnDelete()
         */
        Blueprint::macro('belongsToUuid', function (
            string $name,
            ?string $table = null,
            bool $nullable = false,
            string $onDelete = 'cascade'
        ) {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            $column = Str::endsWith($name, '_id') ? $name : ($name.'_id');

            $col = $this->foreignUuid($column);
            if ($nullable) {
                $col->nullable();
            }

            // Deduz a tabela se não foi passada
            $base = Str::of($column)->beforeLast('_id')->value();

            if (! $table) {
                // heurística para casos tipo "condominium" -> "condominiums"
                $table = preg_match('/ium$/', $base)
                    ? preg_replace('/ium$/', 'iums', $base)
                    : Str::plural($base);
            }

            $fk = $col->constrained($table);

            match (strtolower($onDelete)) {
                'cascade'   => $fk->cascadeOnDelete(),
                'restrict'  => $fk->restrictOnDelete(),
                'set null'  => $fk->nullOnDelete(),
                default     => $fk->noActionOnDelete(),
            };

            return $fk;
        });
    }
}
