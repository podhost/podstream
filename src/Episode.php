<?php
namespace Podhost\Podstream;

use Podhost\Podstream\Support\Models\ModelHasUuid as Model;

class Episode extends Model
{

    /**
     * Belongs to podcast
     *
     * @return mixed
     */
    public function podcast()
    {
        return $this->belongsTo(Podstream::podcastModel());
    }


    /**
     * Get the status of the episode
     *
     * @return string
     */
    public function status()
    {
        if ( !$this->published_at ) {
            return 'draft';
        } else {
            // can be published or scheduled of the date is in the future
            if ( $this->published_at->gt(now())) {
                return 'scheduled';
            } else {
                return 'published';
            }
        }
    }

    /**
     * Purge all of the episode's resources.
     *
     * @return void
     */
    public function purge()
    {
        $this->delete();
    }
}
