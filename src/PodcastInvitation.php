<?php
namespace Podhost\Podstream;

use Podhost\Podstream\Support\Models\ModelHasUuid as Model;

class PodcastInvitation extends Model
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
    public function team()
    {
        return $this->belongsTo(Podstream::podcastModel());
    }
}
