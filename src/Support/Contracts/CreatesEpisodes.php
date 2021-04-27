<?php
namespace Podhost\Podstream\Support\Contracts;

interface CreatesEpisodes
{

    /**
     * Validate and create a new episode for the given podcast.
     *
     * @param $user
     * @param $podcast
     * @param array $input
     * @return mixed
     */
    public function create($user, $podcast, array $input);
}
