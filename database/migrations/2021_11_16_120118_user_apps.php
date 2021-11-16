<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class UserApps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_apps', function ($table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->string('store_name', 255);
            $table->string('sub_domain', 255);
            $table->boolean('is_trial', 0);
            $table->timestampTz('trial_end_at', 0)->nullable();
            $table->timestampTz('subscription_ends_at', 0)->nullable();
            $table->timestamps();
            $table->softDeletesTz('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_apps');
    }
}
