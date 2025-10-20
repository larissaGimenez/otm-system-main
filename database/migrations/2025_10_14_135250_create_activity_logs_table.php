<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {

            $table->uuidPrimary();          
            $table->uuidMorphs('subject');   
            $table->belongsToUuid('causer', 'users', true, 'set null'); 
            $table->string('description');
            $table->string('causer_name');
            $table->timestamps();

            // Índices 
            $table->index(['causer_id', 'created_at']);
            $table->comment('Application activity/audit logs');
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
