<?php
namespace Podhost\Podstream\Events;

use Illuminate\Foundation\Events\Dispatchable;

class AddingPodcastMember
{
    use Dispatchable;

    /**
     * The podcast instance.
     *
     * @var mixed
     */
    public $podcast;

    /**
     * The podcast member being added.
     *
     * @var mixed
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $podcast
     * @param  mixed  $user
     * @return void
     */
    public function __construct($podcast, $user)
    {
        $this->podcast = $podcast;
        $this->user = $user;
    }
}
