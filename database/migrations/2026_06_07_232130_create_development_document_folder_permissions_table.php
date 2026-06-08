<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('development_document_folder_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('development_document_folder_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('can_view')->default(true);
            $table->boolean('can_upload')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->timestamps();

            $table->foreign('development_document_folder_id', 'doc_folder_permissions_folder_fk')
                ->references('id')
                ->on('development_document_folders')
                ->cascadeOnDelete();
            $table->unique(['development_document_folder_id', 'user_id'], 'document_folder_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('development_document_folder_permissions');
    }
};
