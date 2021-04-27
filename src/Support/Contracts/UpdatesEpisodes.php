<?php
namespace Podhost\Podstream\Support\Contracts;

interface UpdatesEpisodes
{
    /**
     * Validate and update the given episode's data.
     *
     * @param $podcast
     * @param $episode
     * @param array $input
     * @return mixed
     */
    public function update($podcast, $episode, array $input);
}
