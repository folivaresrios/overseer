<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePermissionRoleTable
 */
class CreatePermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned()->index();
            $table->integer('permission_id')->unsigned()->index();
            $table->timestamps();

            //Setup Foreign Keys
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('permission_id')->references('id')->on('permissions');

            $table->unique(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permission_role');
    }
}
