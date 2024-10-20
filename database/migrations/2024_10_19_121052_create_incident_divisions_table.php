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
        Schema::create('incident_divisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incidentId');
            $table->unsignedBigInteger('divisionId');
            $table->foreign('incidentId')->references('id')->on('incidents')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('divisionId')->references('id')->on('divisions')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('incident_divisions', function (Blueprint $table) {
            $table->dropForeign(['incidentId']);
            $table->dropForeign(['divisionId']);
        });
        Schema::dropIfExists('incident_divisions');
    }
};
