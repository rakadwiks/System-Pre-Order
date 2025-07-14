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
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();

            // Jika kamu ingin foreign key
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('rejected_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Hapus foreign keys terlebih dahulu (jika ditambahkan)
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);

            // Lalu hapus kolom
            $table->dropColumn(['approved_by', 'rejected_by']);
        });
    }
};
