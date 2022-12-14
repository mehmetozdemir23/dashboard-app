<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grumes', function (Blueprint $table) {
            $table->integer('number')->unique();
            $table->double('length', 6, 2);
            $table->double('diameter', 6, 2);
            $table->string('container_number')->nullable();
            $table->foreign('container_number')->references('number')->on('containers')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grumes');
    }
};
