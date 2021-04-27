<?php
namespace Podhost\Podstream\Actions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ValidateEpisodeDeletion
{
    /**
     * Validate that the episode can be deleted by the given user.
     *
     * @param $user
     * @param $episode
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return void
     */
    public function validate($user, $episode)
    {
        Gate::forUser($user)->authorize('delete', $episode);
    }
}
