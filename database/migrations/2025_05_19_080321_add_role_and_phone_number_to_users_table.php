<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['admin', 'user'])->default('user');  // Default role user
        $table->string('phone_number')->nullable();
    });
}


public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('role');
        $table->dropColumn('phone_number');
    });
}

};
