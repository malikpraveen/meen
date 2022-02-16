<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectoryFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->int('user_id');
            $table->integer('directory_id');
            $table->text('file_path');
            $table->text('file_name');
            $table->double('file_size');
            $table->varchar('file_type');
            $table->enum('status',['active','inactive','trashed']);
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
        Schema::dropIfExists('directory_files');
    }
}
