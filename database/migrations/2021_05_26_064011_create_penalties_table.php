<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penalties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vehicle_id');
            $table->string('receipt_number', 350);
            $table->date('penalty_date');
            $table->date('payment_date');
            $table->string('status', 350);
            $table->date('notification_date');
            $table->time('penalty_hour');
            $table->string('penalty_article', 350);
            $table->string('penalty', 350);
            $table->string('paying', 350);
            $table->string('source', 350);
            $table->string('unit', 350);
            $table->string('return_id', 350);
            $table->string('pesintutar', 350);
            $table->string('daysisid', 350);
            $table->string('daysisonay', 350);
            $table->string('pdf_url', 350);
            $table->unsignedBigInteger('added_by');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->foreign('added_by')->references('id')->on('users');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penalties');
    }
}
