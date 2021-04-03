<?php

use App\Models\Series;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();

            // Series Details
            $table->string('title', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->text('summary');
            $table->string('excerpt', 500);
            $table->string('author', 50);
            $table->string('legal_status', 50)->default(Series::LEGAL_OTHER);
            $table->boolean('completed')->default(false);

            // 
            $table->json('schedule')->default('[]');
            $table->json('staffs')->default('[]');
            $table->json('arcs')->default('[]');
            $table->json('needs')->default('[]');

            // Foreign Keys
            $table->foreignId('poster_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('published_at')->useCurrent();
        
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('series', function (Blueprint $table) {
        //     $table->dropSoftDeletes();
        // });
        Schema::dropIfExists('series');
    }
}
