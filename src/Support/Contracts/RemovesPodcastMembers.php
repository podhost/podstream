<?php
namespace Podhost\Podstream\Support\Contracts;

interface RemovesPodcastMembers
{

    /**
     * Remove the podcast member from the given podcast.
     *
     * @param $user
     * @param $podcast
     * @param $podcastMember
     * @return mixed
     */
    public function remove($user, $podcast, $podcastMember);
}
