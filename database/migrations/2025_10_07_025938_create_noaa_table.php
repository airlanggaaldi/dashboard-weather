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
        Schema::create('noaa', function (Blueprint $table) {
            $table->string('station_id')->index();
            $table->date('date')->index();

            $table->integer('tempAvg')->nullable()->comment('Average temperature (tenths of a degrees C)');
            $table->integer('tempMax')->nullable()->comment('Maximum temperature (tenths of degrees C)');
            $table->integer('tempMin')->nullable()->comment('Minimum temperature (tenths of degrees C)');
            $table->unsignedInteger('precipitation')->nullable()->comment('Precipitation (tenths of mm)');
            $table->unsignedInteger('snowfall')->nullable()->comment('Snowfall (mm)');
            $table->unsignedInteger('snowDepth')->nullable()->comment('Snow depth (mm)');
            $table->unsignedTinyInteger('percentDailySun')->nullable()->comment('Daily percent of possible sunshine (percent)');
            $table->unsignedInteger('averageWindSpeed')->nullable()->comment('Average daily wind speed (tenths of meters per second)');
            $table->unsignedInteger('maxWindSpeed')->nullable()->comment('Peak gust wind speed (tenths of meters per second)');

            $table->enum('weatherType', [
                'Normal','Fog','Heavy Fog','Thunder','Small Hail','Hail','Glaze',
                'Dust/Ash','Smoke/Haze','Blowing/Drifting Snow','Tornado','High Winds',
                'Blowing Spray','Mist','Drizzle','Freezing Drizzle','Rain',
                'Freezing Rain','Snow','Unknown Precipitation','Ground Fog','Freezing Fog'
            ])->default('Normal');

            $table->string('location')->nullable();
            $table->float('elevation')->nullable();
            $table->string('name')->nullable();

            $table->primary(['station_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noaa');
    }
};
