<?php
namespace Podhost\Podstream\Support\Contracts;

interface CreatesPodcasts
{

    /**
     * Validate and create a new podcast for the given user.
     *
     * @param $user
     * @param array $input
     * @return mixed
     */
    public function create($user, array $input);
}
