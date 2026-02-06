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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete(); //llaves foraneas, limites, borrar ambos
                $table->foreignId('blood_type_id')->nullable()->constrained()->nullOnDelete(); //llave foranea
                $table->string('allergies')->nullable();
                $table->string('chronic_conditions')->nullable();
                $table->string('surgical_history')->nullable();
                $table->string('family_history')->nullable();
                $table->string('observations')->nullable();
                $table->string('emergency_contact')->nullable();
                $table->string('emergency_contact_relationship')->nullable();
                $table->timestamps();


        });
    }

    //Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
    
        /*public function up()
            {
                Schema::table('patients', function (Blueprint $table) {
                    $table->foreignId('user_id')
                        ->constrained()
                        ->cascadeOnDelete();
                });
            }

            public function down()
            {
                Schema::table('patients', function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                });
            }*/

};
