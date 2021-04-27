<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePodcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('podcasts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('title', 2047);
            $table->text('description')->nullable();
            $table->enum('type', config('podstream.podcast_types'));
            $table->string('timezone');
            $table->string('language', 5);
            $table->boolean('is_mp3_download_allowed');
            $table->boolean('is_explicit');
            $table->json('tags')->nullable();
            $table->json('authors');
            $table->string('owner');
            $table->string('owner_email');
            $table->string('copyright')->nullable();
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
        Schema::dropIfExists('podcasts');
    }
}
