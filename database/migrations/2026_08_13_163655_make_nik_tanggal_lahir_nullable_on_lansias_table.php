<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lansias', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->string('nomor_telepon', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('lansias', function (Blueprint $table) {
            $table->string('nik', 16)->nullable(false)->change();
            $table->date('tanggal_lahir')->nullable(false)->change();
        });
    }
};
