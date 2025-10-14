<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_user', function (Blueprint $table) {

            $table->foreignUuid('team_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->primary(['team_id', 'user_id']);
            $table->timestamps();
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_user');
    }
};