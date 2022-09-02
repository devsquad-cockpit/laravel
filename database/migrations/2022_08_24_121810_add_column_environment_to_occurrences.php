<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddColumnEnvironmentToOccurrences extends Migration
{
    public function up(): void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            if (DB::getDriverName() === 'mysql') {
                $jsonEmpty = new Expression('(JSON_ARRAY())');
            } else {
                $jsonEmpty = '[]';
            }

            $table->json('environment')->before('created_at')->default($jsonEmpty);
        });
    }

    public function down(): void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->dropColumn('environment');
        });
    }
};
