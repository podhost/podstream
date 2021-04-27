<?php
namespace App\Models;

use Podhost\Podstream\Podstream;
use Podhost\Podstream\PodcastInvitation as PodstreamPodcastInvitation;

class PodcastInvitation extends PodstreamPodcastInvitation
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'role',
    ];


    /**
     * Get the podcast that the invitation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function podcast()
    {
        return $this->belongsTo(Podstream::podcastModel());
    }
}
