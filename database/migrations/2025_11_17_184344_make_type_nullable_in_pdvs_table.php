<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Pdv\PdvType;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('pdvs', function (Blueprint $table) {
            $table->enum('type', array_column(PdvType::cases(), 'value'))
                  ->nullable()
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('pdvs', function (Blueprint $table) {
            $table->enum('type', array_column(PdvType::cases(), 'value'))
                  ->change();
        });
    }
};