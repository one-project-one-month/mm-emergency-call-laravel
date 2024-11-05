<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emergency_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('service_id');
            $table->foreignId('provider_id');
            $table->timestamp('request_time');
            $table->string('status');
            $table->timestamp('response_time')->nullable();
            $table->string('notes')->nullable();
            $table->string('state')->nullable();
            $table->string('township')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index('user_id', 'idx_user_id');
            $table->index('service_id', 'idx_service_id');
            $table->index('provider_id', 'idx_provider_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_requests');
    }
};
