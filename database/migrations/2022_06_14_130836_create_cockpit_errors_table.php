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
            $table->string('exception', 256);
            $table->text('message');
            $table->integer('code')->default(0);
            $table->text('file');
            $table->text('url')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['exception', 'message', 'file']);
        });
    }

    public function down(): void
    {
        Schema::connection('cockpit')->dropIfExists('cockpit_errors');
    }
}
