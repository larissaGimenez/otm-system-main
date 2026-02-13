<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Campos de fechamento
            $table->text('closure_description')->nullable()->after('description');
            $table->string('closure_media_path')->nullable()->after('closure_description');
            $table->enum('closure_media_type', ['photo', 'video'])->nullable()->after('closure_media_path');
            $table->foreignUuid('closed_by')->nullable()->after('closure_media_type')->constrained('users')->nullOnDelete();
            $table->timestamp('closed_at')->nullable()->after('closed_by');

            // Campos de arquivamento
            $table->timestamp('archived_at')->nullable()->after('closed_at');
            $table->foreignUuid('archived_by')->nullable()->after('archived_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['closed_by']);
            $table->dropForeign(['archived_by']);
            $table->dropColumn([
                'closure_description',
                'closure_media_path',
                'closure_media_type',
                'closed_by',
                'closed_at',
                'archived_at',
                'archived_by',
            ]);
        });
    }
};
