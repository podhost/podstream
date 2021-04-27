<?php
namespace Podhost\Podstream;


trait BelongsToEpisode
{

    /**
     * Belongs to episode relation
     *
     * @return mixed
     */
    public function episode()
    {
        return $this->belongsTo(Podstream::episodeModel());
    }

}
