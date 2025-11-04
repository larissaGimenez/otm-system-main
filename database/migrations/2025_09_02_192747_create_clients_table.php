<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Client\ClientType;
use App\Enums\General\GeneralBanks;
use App\Enums\General\GeneralPixType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->uuidPrimary(); 

            // Identificação principal
            $table->string('name'); 
            $table->enum('type', array_column(ClientType::cases(), 'value')); 
            $table->string('cnpj', 14)->unique();        

            // Endereço
            $table->string('postal_code', 8)->nullable(); 
            $table->string('street')->nullable();         
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();   
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();
            
            // Dados bancários
            $table->enum('bank', array_column(GeneralBanks::cases(), 'value'))->nullable(); 
            $table->string('agency')->nullable();
            $table->string('account')->nullable();
            $table->string('account_digit', 2)->nullable();     
            $table->enum('pix_type', array_column(GeneralPixType::cases(), 'value'))->nullable();
            $table->string('pix_key')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices 
            $table->index(['city', 'state']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
