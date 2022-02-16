<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateUserSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscription', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('plan_id');
            $table->double('amount')->default(0);
            $table->double('alloted_space')->default(0)->comment('in KB');
            $table->double('current_available_space')->default(0)->comment('in KB');
            $table->timestamp('valid_till');
            $table->timestamp('data_store_till_date')->nullable();
            $table->integer('order_id')->nullable();
            $table->enum('status',['payment_pending','active','terminated','expired'])->default('payment_pending');
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
        Schema::dropIfExists('user_subscription');
    }
}