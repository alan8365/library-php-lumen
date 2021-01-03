<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permission', function (Blueprint $table) {
            $table->string('role_id');
            $table->foreign('role_id')->references('slug')->on('roles')->onDelete('cascade');
            $table->string('permission_id');
            $table->foreign('permission_id')->references('slug')->on('permissions')->onDelete('cascade');
            $table->timestamps();
            $table->primary(array('role_id', 'permission_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permission');
    }
}
