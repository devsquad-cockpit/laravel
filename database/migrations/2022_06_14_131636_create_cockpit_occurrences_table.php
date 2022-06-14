<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCockpitOccurrencesTable extends Migration
{
    public function up(): void
    {
        Schema::connection('cockpit')->create('cockpit_occurrences', function (Blueprint $table) {
            $table->uuid();
            $table->uuid('cockpit_error_uuid');
            $table->text('url')->nullable();
            $table->enum('type', ['web', 'cli', 'queue']);
            $table->text('message');
            $table->integer('code')->default(0);
            $table->text('file');
            $table->json('trace');
            $table->timestamps();

            $table->foreign('cockpit_error_uuid')
                  ->references('uuid')
                  ->on('cockpit_errors')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::connection('cockpit')->dropIfExists('cockpit_occurrences');
    }
}
