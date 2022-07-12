<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOccurrencesTable extends Migration
{
    public function up(): void
    {
        Schema::connection('cockpit')->create('occurrences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('error_id')->references('id')->on('error')->cascadeOnDelete();
            $table->string('type');
            $table->json('trace')->nullable();
            $table->json('debug')->nullable();
            $table->json('app')->nullable();
            $table->json('user')->nullable();
            $table->json('context')->nullable();
            $table->json('request')->nullable();
            $table->json('command')->nullable();
            $table->json('job')->nullable();
            $table->json('livewire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('cockpit')->dropIfExists('occurrences');
    }
}
