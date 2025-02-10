<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flippingbook_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->boolean('state')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        DB::table('flippingbook_categories')->insert([
            ['title' => 'General category', 'state' => 1, 'description' => ''],
        ]);

        Schema::create('flippingbook_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('flippingbook_categories')->noActionOnDelete();
            $table->string('title');
            $table->boolean('state')->default(false);
            $table->string('preview')->nullable()->comment('As a preview of a publication.');
            $table->enum('direction', ['right', 'left'])->default('left');
            $table->boolean('show_slider')->default(false);
            $table->string('author')->nullable()->comment('Book author (not admin).');
            $table->boolean('show_author_category')->default(false);
            $table->boolean('show_author_publication')->default(false);
            $table->text('description')->nullable();
            $table->boolean('show_description_category')->default(false);
            $table->boolean('show_description_publication')->default(false);
            $table->timestamps();
        });

        Schema::create('flippingbook_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publication_id')->constrained('flippingbook_publications')->cascadeOnDelete();
            $table->string('title');
            $table->integer('ordering');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flippingbook_categories');
        Schema::dropIfExists('flippingbook_publications');
        Schema::dropIfExists('flippingbook_pages');
    }
};
