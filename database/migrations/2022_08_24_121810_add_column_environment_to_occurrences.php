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
            $empty = '[]';

            if (DB::getDriverName() === 'mysql') {
                $empty = new Expression('(JSON_ARRAY())');
            }

            $table->json('environment')->before('created_at')->default($empty);
        });
    }

    public function down(): void
    {
        Schema::table('occurrences', function (Blueprint $table) {
            $table->dropColumn('environment');
        });
    }
};
