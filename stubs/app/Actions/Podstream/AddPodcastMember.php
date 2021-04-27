<?php
namespace App\Actions\Podstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Podhost\Podstream\Support\Contracts\AddsPodcastMembers;
use Podhost\Podstream\Events\AddingPodcastMember;
use Podhost\Podstream\Events\PodcastMemberAdded;
use Podhost\Podstream\Podstream;
use Podhost\Podstream\Rules\Role;

class AddPodcastMember implements AddsPodcastMembers
{
    /**
     * Add a new podcast member to the given podcast.
     *
     * @param $user
     * @param $podcast
     * @param string $email
     * @param string|null $role
     * @return mixed|void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function add($user, $podcast, string $email, string $role = null)
    {
        Gate::forUser($user)->authorize('addPodcastMember', $podcast);

        $this->validate($podcast, $email, $role);

        $newPodcastMember = Podstream::findUserByEmailOrFail($email);

        AddingPodcastMember::dispatch($podcast, $newPodcastMember);

        $podcast->users()->attach(
            $newPodcastMember, ['role' => $role]
        );

        PodcastMemberAdded::dispatch($podcast, $newPodcastMember);
    }


    /**
     * Validate the add member operation.
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
        ], $this->rules(), [
            'email.exists' => __('We were unable to find a registered user with this email address.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnPodcast($podcast, $email)
        )->validateWithBag('addPodcastMember');
    }

    /**
     * Get the validation rules for adding a team member.
     *
     * @return array
     */
    protected function rules()
    {
        return array_filter([
            'email' => ['required', 'email', 'exists:users'],
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
                __('This user already belongs to the team.')
            );
        };
    }

}
