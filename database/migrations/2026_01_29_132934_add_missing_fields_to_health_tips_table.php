<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('health_tips', function (Blueprint $table) {
            $table->text('content')->nullable()->after('description');
            $table->string('author')->nullable()->after('content');
            $table->timestamp('published_at')->nullable()->after('author');
            $table->string('read_more_url')->nullable()->after('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_tips', function (Blueprint $table) {
            $table->dropColumn(['content', 'author', 'published_at', 'read_more_url']);
        });
    }
};
