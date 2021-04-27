<?php
namespace App\Actions\Podstream;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Podhost\Podstream\Support\Contracts\RemovesPodcastMembers;
use Podhost\Podstream\Events\PodcastMemberRemoved;

class RemovePodcastMember implements RemovesPodcastMembers
{
    /**
     * Remove the podcast member from the given podcast.
     *
     * @param $user
     * @param $podcast
     * @param $podcastMember
     * @return mixed|void
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function remove($user, $podcast, $podcastMember)
    {
        $this->authorize($user, $podcast, $podcastMember);

        $this->ensureUserDoesNotOwnPodcast($podcastMember, $podcast);

        $podcast->removeUser($podcastMember);

        PodcastMemberRemoved::dispatch($podcast, $podcastMember);
    }

    /**
     * Authorize that the user can remove the podcast member.
     *
     * @param $user
     * @param $podcast
     * @param $podcastMember
     * @throws AuthorizationException
     */
    protected function authorize($user, $podcast, $podcastMember)
    {
        if (! Gate::forUser($user)->check('removePodcastMember', $podcast) &&
            $user->id !== $podcastMember->id) {
            throw new AuthorizationException;
        }
    }

    /**
     * Ensure that the currently authenticated user does not own the podcast.
     *
     * @param $podcastMember
     * @param $podcast
     * @throws ValidationException
     */
    protected function ensureUserDoesNotOwnPodcast($podcastMember, $podcast)
    {
        if ($podcastMember->id === $podcast->owner->id) {
            throw ValidationException::withMessages([
                'team' => [__('You may not leave a podcast that you created.')],
            ])->errorBag('removePodcastMember');
        }
    }
}
