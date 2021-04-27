<?php
namespace App\Actions\Podstream;

use Podhost\Podstream\Support\Contracts\InvitesPodcastMembers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Podhost\Podstream\Events\InvitingPodcastMember;
use Podhost\Podstream\Podstream;
use Podhost\Podstream\Mail\PodcastInvitation;
use Podhost\Podstream\Rules\Role;

class InvitePodcastMember implements InvitesPodcastMembers
{
    /**
     * Invite a new podcast member to the given podcast.
     *
     * @param $user
     * @param $podcast
     * @param string $email
     * @param string|null $role
     * @return mixed|void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function invite($user, $podcast, string $email, string $role = null)
    {
        Gate::forUser($user)->authorize('addPodcastMember', $podcast);

        $this->validate($podcast, $email, $role);

        InvitingPodcastMember::dispatch($podcast, $email, $role);

        $invitation = $podcast->podcastInvitations()->create([
            'email' => $email,
            'role' => $role,
        ]);

        Mail::to($email)->send(new PodcastInvitation($invitation));
    }

    /**
     * Validate the invite member operation.
     *
     * @param  mixed  $podcast
     * @param  string  $email
     * @param  string|null  $role
     * @return void
     */
    protected function validate($podcast, string $email, ?string $role)
    {
        Validator::make([
            'email' => $email,
            'role' => $role,
        ], $this->rules($podcast), [
            'email.unique' => __('This user has already been invited to the podcast.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnPodcast($podcast, $email)
        )->validateWithBag('addPodcastMember');
    }

    /**
     * Get the validation rules for inviting a podcast member.
     *
     * @param  mixed  $podcast
     * @return array
     */
    protected function rules($podcast)
    {
        return array_filter([
            'email' => ['required', 'email', Rule::unique('podcast_invitations')->where(function ($query) use ($podcast) {
                $query->where('podcast_id', $podcast->id);
            })],
            'role' => Podstream::hasRoles()
                ? ['required', 'string', new Role]
                : null,
        ]);
    }

    /**
     * Ensure that the user is not already on the podcast.
     *
     * @param  mixed  $podcast
     * @param  string  $email
     * @return \Closure
     */
    protected function ensureUserIsNotAlreadyOnPodcast($podcast, string $email)
    {
        return function ($validator) use ($podcast, $email) {
            $validator->errors()->addIf(
                $podcast->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the podcast.')
            );
        };
    }
}
