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
        Schema::create('arsip_pergub', function (Blueprint $table) {
            $table->id();
            $table->string('nosurat');
            $table->date('tglsurat');
            $table->string('perihal');
            $table->string('isiringkas');
            $table->string('namafile');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_pergub');
    }
};
