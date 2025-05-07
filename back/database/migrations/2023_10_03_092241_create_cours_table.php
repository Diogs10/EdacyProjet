<?php

use App\Models\ModuleProf;
use App\Models\Semestre;
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
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->integer('heureTotal');
            $table->foreignIdFor(Semestre::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ModuleProf::class)->constrained()->cascadeOnDelete();
            $table->enum('termine',[0,1]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours');
    }
};
