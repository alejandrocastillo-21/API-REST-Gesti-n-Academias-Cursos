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
    Schema::create('course_student', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('course_id');
        $table->unsignedBigInteger('student_id');
        $table->timestamp('enrolled_at')->nullable(); // Fecha y hora de la matrícula
        $table->timestamps();

        $table->foreign('course_id')->references('id')->on('courses')->onDelete('restrict');
        $table->foreign('student_id')->references('id')->on('students')->onDelete('restrict');

        // Evita matricular dos veces al mismo alumno en el mismo curso.
        // Mira las DOS columnas juntas: la pareja no puede repetirse.
        $table->unique(['course_id', 'student_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_student');
    }
};
