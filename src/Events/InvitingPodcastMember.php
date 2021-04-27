<?php
namespace Podhost\Podstream\Events;

use Illuminate\Foundation\Events\Dispatchable;

class InvitingPodcastMember
{
    use Dispatchable;

    /**
     * The podcast instance.
     *
     * @var mixed
     */
    public $podcast;

    /**
     * The email address of the invitee.
     *
     * @var mixed
     */
    public $email;

    /**
     * The role of the invitee.
     *
     * @var mixed
     */
    public $role;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $podcast
     * @param  mixed  $email
     * @param  mixed  $role
     * @return void
     */
    public function __construct($podcast, $email, $role)
    {
        $this->podcast = $podcast;
        $this->email = $email;
        $this->role = $role;
    }
}
