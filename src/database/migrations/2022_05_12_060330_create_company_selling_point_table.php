<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySellingPointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_selling_point', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('selling_point_id');
            $table->timestamps();

            $table->unique(['company_id', 'selling_point_id']);
            $table->foreign('company_id')
                ->references('id')
                ->on('companies');
            $table->foreign('selling_point_id')
                ->references('id')
                ->on('selling_points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_selling_point');
    }
}
