<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email',191)->unique();
            $table->string('country_code');
            $table->string('mobile_number');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile_pic')->nullable();
            $table->enum('gender',['1','2','3'])->comment("1=Male,2=Female,3=Others")->nullable();
            $table->string('device_token',1000)->nullable();
            $table->enum('device_type', ['android', 'ios','web']);
            $table->enum('status',['active','inactive','trashed']);
            $table->enum('is_otp_verified',['yes','no']);
            $table->enum('type',['admin','user']);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
