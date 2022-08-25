<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnEnvironmentToOccurrences extends Migration
{
    public function up():void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->json('environment')->default('[]');
        });
    }

    public function down():void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->dropColumn('environment');
        });
    }
};
