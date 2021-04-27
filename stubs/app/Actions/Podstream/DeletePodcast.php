<?php
namespace App\Actions\Podstream;

use Podhost\Podstream\Support\Contracts\DeletesPodcasts;

class DeletePodcast implements DeletesPodcasts
{
    /**
     * Delete the given podcast.
     *
     * @param  mixed  $podcast
     * @return void
     */
    public function delete($podcast)
    {
        $podcast->purge();
    }
}
