<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Enums\Pdv\PdvStatus as OldStatusEnum;
use App\Enums\Pdv\PdvType as OldTypeEnum;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pdv_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); 
            $table->string('color')->default('secondary')->nullable(); 
            $table->timestamps();
        });

        Schema::create('pdv_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        foreach (OldStatusEnum::cases() as $status) {
            DB::table('pdv_statuses')->insert([
                'name'  => $status->getLabel(),
                'slug'  => $status->value,
                'color' => $status->getColorClass(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach (OldTypeEnum::cases() as $type) {
            DB::table('pdv_types')->insert([
                'name'  => $type->getLabel(), 
                'slug'  => $type->value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('pdvs', function (Blueprint $table) {
            $table->foreignId('pdv_status_id')->nullable()->constrained('pdv_statuses');
            $table->foreignId('pdv_type_id')->nullable()->constrained('pdv_types');
        });

        DB::statement("
            UPDATE pdvs 
            SET pdv_status_id = (SELECT id FROM pdv_statuses WHERE slug = pdvs.status)
        ");

        DB::statement("
            UPDATE pdvs 
            SET pdv_type_id = (SELECT id FROM pdv_types WHERE slug = pdvs.type)
        ");

        // 6. LIMPEZA FINAL
        Schema::table('pdvs', function (Blueprint $table) {
            $table->dropColumn(['status', 'type']);
            $table->foreignId('pdv_status_id')->nullable(false)->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('pdvs', function (Blueprint $table) {
            $table->dropForeign(['pdv_status_id']);
            $table->dropForeign(['pdv_type_id']);
            $table->dropColumn(['pdv_status_id', 'pdv_type_id']);
            $table->string('status')->nullable();
            $table->string('type')->nullable();
        });

        Schema::dropIfExists('pdv_statuses');
        Schema::dropIfExists('pdv_types');
    }
};