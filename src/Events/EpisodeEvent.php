<?php
namespace Podhost\Podstream\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class EpisodeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The podcast instance.
     *
     * @var \App\Models\Episode
     */
    public $episode;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Episode  $episode
     * @return void
     */
    public function __construct($episode)
    {
        $this->episode = $episode;
    }
}
