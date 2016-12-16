<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesPermissionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('profile_id')->unsigned()->index();
            $table->integer('permission_id')->unsigned()->index();
            $table->timestamps();

            //Setup Foreign Keys
            $table->foreign('profile_id')->references('id')->on('profiles');
            $table->foreign('permission_id')->references('id')->on('permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profiles_permissions');
    }

}
