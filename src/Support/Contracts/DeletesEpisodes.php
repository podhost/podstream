<?php
namespace Podhost\Podstream\Support\Contracts;

interface DeletesEpisodes
{
    /**
     * Delete the given episode.
     *
     * @param  mixed  $episode
     * @return void
     */
    public function delete($episode);
}
