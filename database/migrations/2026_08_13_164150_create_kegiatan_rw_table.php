<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan_rw', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatans')->cascadeOnDelete();
            $table->string('rw');
            $table->timestamps();

            $table->unique(['kegiatan_id', 'rw']);
            $table->index('rw');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_rw');
    }
};
