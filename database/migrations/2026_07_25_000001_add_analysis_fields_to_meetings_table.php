<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->text('conclusion')->nullable()->after('sentiment');
            $table->json('conversation_flow')->nullable()->after('conclusion');
            $table->json('speaker_sentiments')->nullable()->after('conversation_flow');
            $table->json('mom')->nullable()->after('speaker_sentiments');
        });
    }

    public function down(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn(['conclusion', 'conversation_flow', 'speaker_sentiments', 'mom']);
        });
    }
};
