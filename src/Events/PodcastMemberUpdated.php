<?php
namespace Podhost\Podstream\Events;

use Illuminate\Foundation\Events\Dispatchable;

class PodcastMemberUpdated
{
    use Dispatchable;

    /**
     * The podcast instance.
     *
     * @var mixed
     */
    public $podcast;

    /**
     * The podcast member that was updated.
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
        $this->team = $podcast;
        $this->user = $user;
    }
}
