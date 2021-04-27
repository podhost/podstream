<?php
namespace Podhost\Podstream\Support\Contracts;

interface InvitesPodcastMembers
{
    /**
     * Invite a new podcast member to the given podcast.
     *
     * @param $user
     * @param $podcast
     * @param string $email
     * @param string|null $role
     * @return mixed
     */
    public function invite($user, $podcast, string $email, string $role = null);
}
