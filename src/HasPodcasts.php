<?php
namespace Podhost\Podstream;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

trait HasPodcasts
{
    /**
     * Determine if the given podcast is the current podcast.
     *
     * @param  mixed  $podcast
     * @return bool
     */
    public function isCurrentPodcast($podcast)
    {
        return $podcast->id === $this->currentPodcast->id;
    }

    /**
     * Get the current podcast of the user's context.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentPodcast()
    {
        if (is_null($this->current_podcast_id) && $this->id) {
            // @todo switch to the last added podcast
            $this->switchPodcast($this->allPodcasts()->first());
        }

        return $this->belongsTo(Podstream::podcastModel(), 'current_podcast_id');
    }

    /**
     * Switch the user's context to the given podcast.
     *
     * @param  mixed $podcast
     * @return bool
     */
    public function switchPodcast($podcast)
    {
        if (! $this->belongsToPodcast($podcast)) {
            return false;
        }

        $this->forceFill([
            'current_podcast_id' => $podcast->id,
        ])->save();

        $this->setRelation('currentPodcast', $podcast);

        return true;
    }


    /**
     * Get all of the podcasts the user owns or belongs to.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allPodcasts()
    {
        return $this->ownedPodcasts->merge($this->podcasts)->sortBy('created_at');
    }


    /**
     * Get all of the podcasts the user owns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownedPodcasts()
    {
        return $this->hasMany(Podstream::podcastModel());
    }

    /**
     * Get all of the podcasts the user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function podcasts()
    {
        return $this->belongsToMany(Podstream::podcastModel(), Podstream::membershipModel())
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }


    /**
     * Determine if the user owns the given podcast.
     *
     * @param  mixed  $podcast
     * @return bool
     */
    public function ownsPodcast($podcast)
    {
        return $this->id == $podcast->{$this->getForeignKey()};
    }

    /**
     * Determine if the user belongs to the given podcast.
     *
     * @param  mixed  $podcast
     * @return bool
     */
    public function belongsToPodcast($podcast)
    {
        return $this->podcasts->contains(function ($t) use ($podcast) {
                return $t->id === $podcast->id;
            }) || $this->ownsPodcast($podcast);
    }

    /**
     * Get the role that the user has on the podcast.
     *
     * @param  mixed  $podcast
     * @return \Podhost\Podstream\Role
     */
    public function podcastRole($podcast)
    {

        if ($this->ownsPodcast($podcast)) {
            return new OwnerRole;
        }

        if (! $this->belongsToPodcast($podcast)) {
            return;
        }

        return Podstream::findRole($podcast->users->where(
            'id', $this->id
        )->first()->membership->role);
    }


    /**
     * Determine if the user has the given role on the given podcast.
     *
     * @param  mixed  $podcast
     * @param  string  $role
     * @return bool
     */
    public function hasPodcastRole($podcast, string $role)
    {
        if ($this->ownsPodcast($podcast)) {
            return true;
        }

        return $this->belongsToPodcast($podcast) && optional(Podstream::findRole($podcast->users->where(
                'id', $this->id
            )->first()->membership->role))->key === $role;
    }


    /**
     * Get the user's permissions for the given podcast.
     *
     * @param  mixed  $podcast
     * @return array
     */
    public function podcastPermissions($podcast)
    {
        if ($this->ownsPodcast($podcast)) {
            return ['*'];
        }

        if (! $this->belongsToPodcast($podcast)) {
            return [];
        }

        return $this->podcastRole($podcast)->permissions;
    }

    /**
     * Determine if the user has the given permission on the given podcast.
     *
     * @param  mixed  $podcast
     * @param  string  $permission
     * @return bool
     */
    public function hasPodcastPermission($podcast, string $permission)
    {
        if ($this->ownsPodcast($podcast)) {
            return true;
        }

        if (! $this->belongsToPodcast($podcast)) {
            return false;
        }

        if ( in_array(HasApiTokens::class, class_uses_recursive($this)) &&
            ! $this->tokenCan($permission) &&
            $this->currentAccessToken() !== null) {
            return false;
        }

        $permissions = $this->podcastPermissions($podcast);

        return in_array($permission, $permissions) ||
            in_array('*', $permissions) ||
            (Str::endsWith($permission, ':create') && in_array('*:create', $permissions)) ||
            (Str::endsWith($permission, ':update') && in_array('*:update', $permissions));
    }
}
