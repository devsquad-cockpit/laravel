<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->dateTime('resolved_at')->nullable();
            $table->dateTime('last_occurrence_at')->nullable();
            $table->timestamps();

            $table->index('exception');

            if (DB::getDriverName() === 'mysql') {
                $table->rawIndex('message(255)', 'errors_message_index');
                $table->rawIndex('file(255)', 'errors_file_index');
                return;
            }

            $table->index('message');
            $table->index('file');
        });
    }

    public function down(): void
    {
        Schema::connection('cockpit')->dropIfExists('cockpit_errors');
    }
}
