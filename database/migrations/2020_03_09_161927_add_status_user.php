<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // добавление статуса пользователя
        Schema::table('users', function (Blueprint $table) {
            $table->text('status')->nullable();
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // откат изменений в бд
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
