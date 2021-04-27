<?php
namespace Podhost\Podstream;

use Podhost\Podstream\Support\Models\ModelHasUuid as Model;

class Podcast extends Model
{

    /**
     * Get all of the episodes owned by this podcast.
     *
     * @return mixed
     */
    public function episodes()
    {
        return $this->hasMany(Podstream::episodeModel());
    }


    /**
     * Get the public episodes
     *
     * @return mixed
     */
    public function publicEpisodes()
    {
        return $this->episodes()
            ->where('is_private', false)
            ->where('released_at', '!=', null)
            ->where('released_at', '>=', now())
            ->orderBy('released_at', 'asc');
    }

    /**
     * Get the owner of the podcast.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(Podstream::userModel(), 'user_id');
    }

    /**
     * Get all of the podcast's users including its owner.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allUsers()
    {
        return $this->users->merge([$this->owner]);
    }

    /**
     * Get all of the users that belong to the podcast.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Podstream::userModel(), Podstream::membershipModel())
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }

    /**
     * Determine if the given user belongs to the podcast.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function hasUser($user)
    {
        return $this->users->contains($user) || $user->ownsPodcast($this);
    }

    /**
     * Determine if the given email address belongs to a user on the podcast.
     *
     * @param  string  $email
     * @return bool
     */
    public function hasUserWithEmail(string $email)
    {
        return $this->allUsers()->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }

    /**
     * Determine if the given user has the given permission on the podcast.
     *
     * @param  \App\Models\User  $user
     * @param  string  $permission
     * @return bool
     */
    public function userHasPermission($user, $permission)
    {
        return $user->hasPodcastPermission($this, $permission);
    }


    /**
     * Get all of the pending user invitations for the podcast.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function podcastInvitations()
    {
        return $this->hasMany(Podstream::podcastInvitationModel());
    }


    /**
     * Remove the given user from the podcast.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function removeUser($user)
    {
        if ($user->current_podcast_id === $this->id) {
            $user->forceFill([
                'current_podcast_id' => null,
            ])->save();
        }

        $this->users()->detach($user);
    }

    /**
     * Purge all of the podcast's resources.
     *
     * @return void
     */
    public function purge()
    {
        $this->owner()->where('current_podcast_id', $this->id)
            ->update(['current_podcast_id' => null]);

        $this->users()->where('current_podcast_id', $this->id)
            ->update(['current_podcast_id' => null]);

        $this->users()->detach();

        foreach ( $this->episodes as $episode )
        {
            $episode->delete();
        }

        $this->delete();
    }

}
