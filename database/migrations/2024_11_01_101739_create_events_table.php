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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->index();
            $table->foreignId('user_id')->index();
            $table->json('payload')->nullable();
            $table->timestamps();

            // Here must set foreign relation.
            /*$table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')->onUpdate('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
