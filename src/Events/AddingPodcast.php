<?php
namespace Podhost\Podstream\Events;

use Illuminate\Foundation\Events\Dispatchable;

class AddingPodcast
{
    use Dispatchable;

    /**
     * The podcast owner.
     *
     * @var mixed
     */
    public $owner;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $owner
     * @return void
     */
    public function __construct($owner)
    {
        $this->owner = $owner;
    }
}
