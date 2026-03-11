<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('statements', function (Blueprint $table) {
            $table->enum('status', ['submitted', 'approved', 'rejected', 'draft'])
                ->default('draft')
                ->after('number');
            $table->softDeletes();
            $table->bigInteger('resource_id')->unsigned()->nullable();
            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
            $table->bigInteger('approved_by')->unsigned()->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('status_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('statement_id')->unsigned();
            $table->string('old_status');
            $table->string('new_status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('statements', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::dropIfExists('status_history');
    }
};
