<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\DB;
use Podhost\Podstream\Support\Contracts\DeletesPodcasts;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * The team deleter implementation.
     *
     * @var \Laravel\Jetstream\Contracts\DeletesTeams
     */
    protected $deletesPodcasts;

    /**
     * Create a new action instance.
     *
     * @param  \Podhost\Podstream\Support\Contracts\DeletesPodcasts  $deletesPodcasts
     * @return void
     */
    public function __construct(DeletesPodcasts $deletesPodcasts)
    {
        $this->deletesPodcasts = $deletesPodcasts;
    }

    /**
     * Delete the given user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function delete($user)
    {
        DB::transaction(function () use ($user) {
            $this->deletePodcasts($user);
            $user->deleteProfilePhoto();
            $user->tokens->each->delete();
            $user->delete();
        });
    }

    /**
     * Delete the podcasts associations attached to the user.
     *
     * @param  mixed  $user
     * @return void
     */
    protected function deletePodcasts($user)
    {
        $user->podcasts()->detach();

        $user->ownedPodcasts->each(function ($podcast) {
            $this->deletesPodcasts->delete($podcast);
        });
    }
}
