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
            $table->foreignUuid('error_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->text('url')->nullable();
            $table->json('trace')->default('[]');
            $table->json('debug')->default('[]');
            $table->json('app')->default('[]');
            $table->json('user')->default('[]');
            $table->json('context')->default('[]');
            $table->json('request')->default('[]');
            $table->json('command')->default('[]');
            $table->json('job')->default('[]');
            $table->json('livewire')->default('[]');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('cockpit')->dropIfExists('occurrences');
    }
}
