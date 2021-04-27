<?php
namespace Podhost\Podstream\Support\Contracts;

interface UpdatesPodcast
{

    /**
     * Validate and update the given podcast's data.
     *
     * @param $user
     * @param $podcast
     * @param array $input
     * @return mixed
     */
    public function update($user, $podcast, array $input);
}
