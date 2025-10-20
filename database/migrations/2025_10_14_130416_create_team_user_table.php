<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_user', function (Blueprint $table) {
            $table->belongsToUuid('team');             
            $table->belongsToUuid('user', 'users');    
            $table->primary(['team_id', 'user_id']);
            $table->index('user_id');
            $table->timestamps();
            $table->comment('Membership pivot between teams and users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_user');
    }
};
