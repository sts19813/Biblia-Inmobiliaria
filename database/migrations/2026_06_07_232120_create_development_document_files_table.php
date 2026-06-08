<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('development_document_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('development_document_folder_id');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('original_name');
            $table->string('path');
            $table->string('disk')->default('public');
            $table->string('mime_type')->nullable();
            $table->string('extension', 20)->nullable();
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->foreign('development_document_folder_id', 'doc_files_folder_fk')
                ->references('id')
                ->on('development_document_folders')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('development_document_files');
    }
};
