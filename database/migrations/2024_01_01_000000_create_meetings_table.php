<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->string('topic')->default('General');
            $table->string('priority')->default('Normal');
            $table->text('priority_reason')->nullable();
            $table->json('action_items')->nullable();
            $table->json('key_decisions')->nullable();
            $table->string('sentiment')->nullable();
            $table->string('duration_estimate')->nullable();
            $table->longText('transcript')->nullable();
            $table->timestamp('date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
