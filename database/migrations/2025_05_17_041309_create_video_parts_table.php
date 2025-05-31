<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('video_parts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('video_id')->constrained()->onDelete('cascade'); // Relasi ke Video
        $table->integer('part_number')->nullable(false); // Tidak bisa null
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('thumbnail_path')->nullable();
        $table->string('reference_url')->nullable();
        $table->timestamps();

        // Unique constraint untuk video_id dan part_number
        $table->unique(['video_id', 'part_number']); // Kombinasi video_id dan part_number harus unik
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_parts');
    }
};
