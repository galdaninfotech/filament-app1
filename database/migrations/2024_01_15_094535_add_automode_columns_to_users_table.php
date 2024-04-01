<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Fortify\Fortify;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('autotick')
                ->after('two_factor_confirmed_at')
                ->default(0);
            $table->integer('autoclaim')
                ->after('autotick')
                ->default(0);

            $table->string('google_id')->nullable()
                ->after('autoclaim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('autotick');
            $table->dropColumn('autoclaim');
        });
    }
};
