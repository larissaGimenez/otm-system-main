<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('cpf', '')->update(['cpf' => null]);
        DB::table('users')->where('phone', '')->update(['phone' => null]);

        Schema::table('users', function (Blueprint $table) {
            // Tornando os campos nullable
            $table->string('cpf')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('postal_code')->nullable()->change();
            $table->string('street')->nullable()->change();
            $table->string('number')->nullable()->change();
            $table->string('complement')->nullable()->change();
            $table->string('neighborhood')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('state')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('cpf')->nullable(false)->change();
            
        });
    }
};