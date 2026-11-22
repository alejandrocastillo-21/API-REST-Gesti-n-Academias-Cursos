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
        Schema::create('academies', function (Blueprint $table) {
            $table->id();

            // Columna que guardará el id del usuario dueño.
            // Es un número entero grande sin signo, igual que el "id" de users.
            $table->unsignedBigInteger('user_id');

            $table->string('name');
            $table->text('description')->nullable(); // nullable = puede quedar vacío
            $table->string('address');
            $table->string('phone', 20);
            $table->string('email');
            $table->timestamps();

            // Clave foránea escrita paso a paso:
            // "la columna user_id referencia a la columna id de la tabla users".
            // onDelete('cascade'): si se borra el usuario, se borran sus academias.
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academies');
    }
};
