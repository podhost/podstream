<?php
namespace Podhost\Podstream\Actions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ValidatePodcastDeletion
{
    /**
     * Validate that the podcast can be deleted by the given user.
     *
     * @param $user
     * @param $podcast
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return void
     */
    public function validate($user, $podcast)
    {
        Gate::forUser($user)->authorize('delete', $podcast);
    }
}
