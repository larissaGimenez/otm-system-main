<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('request_user', function (Blueprint $table) {

            $table->foreignUuid('request_id')->constrained('requests');
            $table->foreignUuid('user_id')->constrained('users');
            $table->primary(['request_id', 'user_id']);
            $table->timestamps(); 

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_user');
    }
};