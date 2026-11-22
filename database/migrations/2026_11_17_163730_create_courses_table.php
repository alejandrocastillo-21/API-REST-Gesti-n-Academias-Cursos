<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academy_id'); // A qué academia pertenece
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date');  // Solo fecha
            $table->date('end_date');
            $table->unsignedInteger('capacity'); // Plazas; sin signo = nunca negativo
            $table->timestamps();

            // Cada curso apunta a una academia. Si se borra la academia, se borran sus cursos.
            $table->foreign('academy_id')->references('id')->on('academies')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
