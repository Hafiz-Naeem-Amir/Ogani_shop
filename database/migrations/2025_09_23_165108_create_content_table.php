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
       Schema::create('content', function (Blueprint $table) {
    $table->id();
    $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
    $table->string('h1');
    $table->string('h2');
    $table->string('h3');
    $table->string('p1');
    $table->string('p2');
    $table->string('title');
    $table->string('image')->nullable(); // Corrected: image path as string
    $table->string('design');
    $table->string('keyword');
    $table->string('content');
    $table->timestamps();
    $table->softDeletes();

});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
