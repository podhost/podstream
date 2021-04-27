<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePodcastInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('podcast_invitations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('podcast_id')->index();
            $table->string('email');
            $table->string('role')->nullable();
            $table->timestamps();

            $table->unique(['podcast_id', 'email']);

            $table->foreign('podcast_id')
                ->references('id')
                ->on('podcasts')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('podcast_invitations');
    }
}
