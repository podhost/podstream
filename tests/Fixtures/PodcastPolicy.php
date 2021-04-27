<?php
namespace Podhost\Podstream\Tests\Fixtures;

use App\Models\Podcast;
use Illuminate\Auth\Access\HandlesAuthorization;


class PodcastPolicy
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
    public function view(User $user, Podcast $podcast)
    {
        return $user->belongsToPodcast($podcast);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Podcast  $podcast
     * @return mixed
     */
    public function update(User $user, Podcast $podcast)
    {
        return $user->hasPodcastPermission($podcast, 'podcast:update');
    }

    /**
     * Determine whether the user can add podcast members.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Podcast  $podcast
     * @return mixed
     */
    public function addPodcastMember(User $user, Podcast $podcast)
    {
        return $user->hasPodcastPermission($podcast, 'manager:create');
    }

    /**
     * Determine whether the user can update podcast member permissions.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Podcast  $podcast
     * @return mixed
     */
    public function updatePodcastMember(User $user, Podcast $podcast)
    {
        return $user->hasPodcastPermission($podcast, 'manager:update');
    }

    /**
     * Determine whether the user can remove podcast members.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Podcast  $podcast
     * @return mixed
     */
    public function removePodcastMember(User $user, Podcast $podcast)
    {
        return $user->ownsPodcast($podcast);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Podcast  $podcast
     * @return mixed
     */
    public function delete(User $user, Podcast $podcast)
    {
        return $user->ownsPodcast($podcast);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Podcast  $podcast
     * @return mixed
     */
    public function restore(User $user, Podcast $podcast)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Podcast  $podcast
     * @return mixed
     */
    public function forceDelete(User $user, Podcast $podcast)
    {
        return false;
    }

}
