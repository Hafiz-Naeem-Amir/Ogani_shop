<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('keyword', 255);
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->integer('discount')->nullable();
            $table->text('meta_description');
            $table->longText('content_description');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('variant_id')->constrained('variants')->onDelete('cascade');
            $table->json('variant_list'); // array of variants
            $table->json('images')->nullable(); // multiple images
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

