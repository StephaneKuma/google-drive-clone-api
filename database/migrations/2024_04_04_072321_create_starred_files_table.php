<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('starred_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')
                ->nullable()
                ->constrained('files')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_shares', function (Blueprint $table) {
            $table->dropConstrainedForeignId('file_id');
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::dropIfExists('starred_files');
    }
};
