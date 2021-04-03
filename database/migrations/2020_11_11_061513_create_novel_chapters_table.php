<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNovelChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('novel_chapters', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('content');
            $table->unsignedInteger('chapter');
            $table->unsignedInteger('chapter_part')->nullable();
            $table->boolean('nsfw')->default(false);

            $table->foreignId('series_id')->references('id')->on('series')->onDelete('cascade');
            $table->foreignId('poster_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('published_at')->useCurrent();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['chapter', 'chapter_part', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('novel_chapters');
    }
}
