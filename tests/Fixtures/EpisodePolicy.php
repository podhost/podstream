<?php
namespace Podhost\Podstream\Tests\Fixtures;

use App\Models\Podcast;
use App\Models\Episode;
use Illuminate\Auth\Access\HandlesAuthorization;

class EpisodePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Podcast  $podcast
     * @return mixed
     */
    public function view(User $user, Episode $episode)
    {
        return $user->belongsToPodcast($episode->podcast);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function createEpisode(User $user, Podcast $podcast)
    {
        return $user->hasPodcastPermission($podcast, 'episode:create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Episode  $episode
     * @return mixed
     */
    public function update(User $user, Episode $episode)
    {
        return $user->hasPodcastPermission($episode->podcast, 'episode:update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Episode  $episode
     * @return mixed
     */
    public function delete(User $user, Episode $episode)
    {
        return $user->hasPodcastPermission($episode->podcast, 'episode:delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Episode  $episode
     * @return mixed
     */
    public function restore(User $user, Episode $episode)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Episode  $episode
     * @return mixed
     */
    public function forceDelete(User $user, Episode $episode)
    {
        return false;
    }
}
