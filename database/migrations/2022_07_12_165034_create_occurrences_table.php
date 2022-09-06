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
            $empty = DB::getDriverName() === 'mysql'
                ? new Expression('(JSON_ARRAY())')
                : '[]';

            $table->uuid('id')->primary();
            $table->foreignUuid('error_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->text('url')->nullable();
            $table->json('trace')->default($empty);
            $table->json('debug')->default($empty);
            $table->json('app')->default($empty);
            $table->json('user')->default($empty);
            $table->json('context')->default($empty);
            $table->json('request')->default($empty);
            $table->json('command')->default($empty);
            $table->json('job')->default($empty);
            $table->json('livewire')->default($empty);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('cockpit')->dropIfExists('occurrences');
    }
}
