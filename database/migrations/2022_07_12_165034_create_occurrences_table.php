<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOccurrencesTable extends Migration
{
    public function up(): void
    {
        Schema::connection('cockpit')->create('occurrences', function (Blueprint $table) {
            if (DB::getDriverName() === 'mysql') {
                $jsonEmpty = new Expression('(JSON_ARRAY())');
            } else {
                $jsonEmpty = '[]';
            }

            $table->uuid('id')->primary();
            $table->foreignUuid('error_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->text('url')->nullable();
            $table->json('trace')->default($jsonEmpty);
            $table->json('debug')->default($jsonEmpty);
            $table->json('app')->default($jsonEmpty);
            $table->json('user')->default($jsonEmpty);
            $table->json('context')->default($jsonEmpty);
            $table->json('request')->default($jsonEmpty);
            $table->json('command')->default($jsonEmpty);
            $table->json('job')->default($jsonEmpty);
            $table->json('livewire')->default($jsonEmpty);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('cockpit')->dropIfExists('occurrences');
    }
}
