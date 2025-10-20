<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('condominiums', function (Blueprint $table) {
            $table->uuidPrimary(); 

            // Identificação principal
            $table->string('name');                     
            $table->string('legal_name');               
            $table->string('cnpj', 14)->unique();       
            $table->string('state_registration')->nullable(); 

            // Contato
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();

            // Endereço
            $table->string('postal_code', 8)->nullable(); 
            $table->string('street')->nullable();         
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();   
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();

            // Mídias/arquivos
            $table->string('logo_path')->nullable();      
            $table->string('contract_path')->nullable();  
            $table->json('attachments')->nullable();      

            $table->timestamps();
            $table->softDeletes();

            // Índices 
            $table->index(['city', 'state']);
            $table->comment('Condominiums that hired the service');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('condominiums');
    }
};
