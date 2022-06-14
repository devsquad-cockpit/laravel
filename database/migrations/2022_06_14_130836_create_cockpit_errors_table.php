<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCockpitErrorsTable extends Migration
{
    public function up(): void
    {
        Schema::connection('cockpit')->create('cockpit_errors', function (Blueprint $table) {
            $table->uuid();
            $table->string('exception', 256);
            $table->timestamp('resolved_at')->nullable();
            $table->bigInteger('occurrences')->default(0);
            $table->bigInteger('affected_users')->default(0);
            $table->timestamp('created_at');
            $table->timestamp('last_occurrence');
        });
    }

    public function down(): void
    {
        Schema::connection('cockpit')->dropIfExists('cockpit_errors');
    }
}
