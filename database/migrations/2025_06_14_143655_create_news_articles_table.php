<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Penulis berita
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Kategori berita
            $table->string('title');
            $table->string('slug')->unique(); // Untuk URL ramah SEO
            $table->text('content');
            $table->string('image')->nullable(); // Path gambar sampul
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable(); // Tanggal publikasi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};