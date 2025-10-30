<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {

            $table->uuid('id')->primary(); 
            $table->string('title');
            $table->text('description')->nullable(); 
            $table->string('type');
            $table->string('priority');
            $table->string('status');
            $table->foreignUuid('area_id')->constrained('areas');
            $table->foreignUuid('requester_id')->constrained('users');
            $table->dateTime('due_at')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('attachment_original_name')->nullable();
            $table->timestamps(); 
            $table->softDeletes(); 
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};