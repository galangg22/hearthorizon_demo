<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
  public function up()
{
    Schema::create('videos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('thumbnail_path')->nullable();
        $table->string('video_url')->nullable();
        $table->timestamps();
    });
}



    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
