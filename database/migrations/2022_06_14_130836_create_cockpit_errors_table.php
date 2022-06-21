<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCockpitErrorsTable extends Migration
{
    public function up(): void
    {
        Schema::connection('cockpit')->create('errors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('exception', 256);
            $table->text('message');
            $table->integer('code')->default(0);
            $table->text('url')->nullable();
            $table->text('file');
            $table->json('trace')->nullable();
            $table->json('debug')->nullable();
            $table->json('app')->nullable();
            $table->json('user')->nullable();
            $table->json('context')->nullable();
            $table->json('request')->nullable();
            $table->json('command')->nullable();
            $table->json('job')->nullable();
            $table->json('livewire')->nullable();
            $table->bigInteger('occurrences')->default(0);
            $table->bigInteger('affected_users')->default(0);
            $table->dateTime('last_occurrence_at');
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('cockpit')->dropIfExists('cockpit_errors');
    }
}
