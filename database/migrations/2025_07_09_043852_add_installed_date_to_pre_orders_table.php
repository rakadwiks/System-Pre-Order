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
        Schema::table('pre_orders', function (Blueprint $table) {
            $table->date('installed_date')->nullable()->after('completed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_orders', function (Blueprint $table) {
            $table->dropColumn('installed_date');
        });
    }
};
