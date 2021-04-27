<?php
namespace Podhost\Podstream\Support\Contracts;

interface DeletesPodcasts
{
    /**
     * Delete the given podcast.
     *
     * @param  mixed  $podcast
     * @return void
     */
    public function delete($podcast);
}
