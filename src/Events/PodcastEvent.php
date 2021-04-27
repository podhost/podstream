<?php
namespace Podhost\Podstream\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class PodcastEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The podcast instance.
     *
     * @var \App\Models\Podcast
     */
    public $podcast;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Podcast  $podcast
     * @return void
     */
    public function __construct($podcast)
    {
        $this->podcast = $podcast;
    }
}
