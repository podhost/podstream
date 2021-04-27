<?php
namespace Podhost\Podstream\Support\Contracts;


interface AddsPodcastMembers
{

    /**
     * Add a new podcast member to the given podcast.
     *
     * @param $user
     * @param $podcast
     * @param string $email
     * @param string|null $role
     * @return mixed
     */
    public function add($user, $podcast, string $email, string $role = null);

}
