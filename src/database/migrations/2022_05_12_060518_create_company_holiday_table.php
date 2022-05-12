<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyHolidayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_holiday', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('day_of_week_id');
            $table->timestamps();

            $table->unique(['company_id', 'day_of_week_id']);
            $table->foreign('company_id')
                ->references('id')
                ->on('companies');
            $table->foreign('day_of_week_id')
                ->references('id')
                ->on('days_of_week');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_holiday');
    }
}
