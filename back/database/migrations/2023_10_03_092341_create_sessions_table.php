<?php

use App\Models\Cours;
use App\Models\Salle;
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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cours::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Salle::class)->nullable();
            $table->string('heureDebut');
            $table->integer('duree');
            $table->date('date');
            $table->enum('etat',[0,1])->default(0);
            $table->boolean('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
