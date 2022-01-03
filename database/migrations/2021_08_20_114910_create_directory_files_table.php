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
            $table->integer('directory_id')->unsigned();
            
            $table->text('file_name');
            $table->string('file_ext');
            $table->text('file_path');
            $table->float('file_size')->default(0);
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
