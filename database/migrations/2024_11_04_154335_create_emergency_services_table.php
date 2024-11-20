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
        Schema::create('emergency_services', function (Blueprint $table) {
            $table->id();
            $table->string('service_type');
            $table->string('service_name');
            $table->string('phone_number');
            $table->longText('location');
            $table->enum('availability',['0','1'])->default('0');
            $table->string('state');
            $table->string('township');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index('service_type', 'idx_service_type');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_services');
    }
};
