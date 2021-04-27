<?php
namespace Podhost\Podstream\Actions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Podhost\Podstream\Events\PodcastMemberUpdated;
use Podhost\Podstream\Podstream;
use Podhost\Podstream\Rules\Role;

class UpdatePodcastMemberRole
{
    /**
     * Update the role for the given team member.
     *
     * @param $user
     * @param $podcast
     * @param $podcastMemberId
     * @param string $role
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($user, $podcast, $podcastMemberId, string $role)
    {
        Gate::forUser($user)->authorize('updatePodcastMember', $podcast);

        Validator::make([
            'role' => $role,
        ], [
            'role' => ['required', 'string', new Role],
        ])->validate();

        $podcast->users()->updateExistingPivot($podcastMemberId, [
            'role' => $role,
        ]);

        PodcastMemberUpdated::dispatch($podcast->fresh(), Podstream::findUserByIdOrFail($podcastMemberId));
    }
}
