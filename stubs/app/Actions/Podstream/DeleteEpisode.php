<?php
namespace App\Actions\Podstream;

use Podhost\Podstream\Support\Contracts\DeletesEpisodes;

class DeleteEpisode implements DeletesEpisodes
{
    /**
     * Delete the given episode.
     *
     * @param  mixed  $episode
     * @return void
     */
    public function delete($episode)
    {
        $episode->purge();
    }
}
