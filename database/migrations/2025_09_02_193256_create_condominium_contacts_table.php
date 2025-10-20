<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Condominium\CondominiumContactType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('condominium_contacts', function (Blueprint $table) {
            $table->uuidPrimary();             
            $table->belongsToUuid('condominium', 'condominiums');  

            $table->string('name');                
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->enum('type', array_column(CondominiumContactType::cases(), 'value'))
                  ->default(CondominiumContactType::OTHER->value);

            $table->timestamps();
            $table->softDeletes();

            // Índices 
            $table->index(['condominium_id', 'type']);
            $table->unique(['condominium_id', 'email']);
            $table->comment('Contacts for each condominium (syndic, janitor, etc.)');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('condominium_contacts');
    }
};
