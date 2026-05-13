<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscription_historiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inscription_id')->constrained()->onDelete('cascade');
            $table->string('ancien_statut');
            $table->string('nouveau_statut');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscription_historiques');
    }
};