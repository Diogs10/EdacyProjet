<?php

use App\Models\ClasseAnnee;
use App\Models\Cours;
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
        Schema::create('classe_annee_cours', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cours::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ClasseAnnee::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classe_annee_cours');
    }
};
