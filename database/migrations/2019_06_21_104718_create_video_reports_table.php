<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_reports', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('report_category_id');
            $table->foreign('report_category_id')
                ->references('id')
                ->on('video_report_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('video_id');
            $table->foreign('video_id')
                ->references('id')
                ->on('videos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->text('info');
            $table->smallInteger('first_time')->nullable();
            $table->smallInteger('second_time')->nullable();
            $table->smallInteger('third_time')->nullable();
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
        Schema::dropIfExists('video_reports');
    }
}
