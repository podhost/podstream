<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->uuid('id')->primary(); // primary key
            $table->uuid('podcast_id')->index()->nullable(); // related to the podcasts table
            $table->string('imported_id')->nullable(); // guid from the RSS feed to remain backward compatible

            // Numeric fields
            $table->integer('episode_number');
            $table->integer('season_number');

            // Textual fields
            $table->string('title', 2047)->nullable();
            $table->string('slug')->nullable()->index();
            $table->enum('episode_type', config('podstream.episode_types'));
            $table->text('summary')->nullable();
            $table->text('notes')->nullable();

            // Json fields
            $table->json('contributors')->nullable();
            $table->json('keywords')->nullable();

            // Modifiers
            $table->string('alternate_url', 2047)->nullable();

            // Boolean fields
            $table->boolean('is_private')->default(false);
            $table->boolean('is_explicit')->default(false);

            // Status related and timestamps
            $table->timestamp('published_at')->nullable()->index();
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
        Schema::dropIfExists('episodes');
    }
}
