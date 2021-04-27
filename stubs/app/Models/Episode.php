<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Podhost\Podstream\Episode as PodstreamEpisode;
use Podhost\Podstream\Events\EpisodeCreated;
use Podhost\Podstream\Events\EpisodeDeleted;
use Podhost\Podstream\Events\EpisodeUpdated;

class Episode extends PodstreamEpisode
{
    use HasFactory, SoftDeletes;

    /**
     * Cast to native types
     *
     * @var array
     */
    protected $casts = [
        'episode_number' => 'integer',
        'season_number' => 'integer',
        'contributors' => 'array',
        'keywords' => 'array',
        'is_private' => 'boolean',
        'is_explicit' => 'boolean',
        'published_at' => 'datetime'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => EpisodeCreated::class,
        'updated' => EpisodeUpdated::class,
        'deleted' => EpisodeDeleted::class,
    ];


    /**
     * Single source of truth for validation rules
     *
     * @return array
     */
    public static function validationRules()
    {
        return [
            'episode_number' => [
                'required',
                'numeric'
            ],
            'season_number' => [
                'required',
                'numeric'
            ],
            'title' => [
                'required',
                'string',
                'max:2000'
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
            ],
            'episode_type' => [
                'required',
                'in:' . implode(config('podstream.episode_types'))
            ],
            'summary' => [
                'nullable',
                'string',
                'max:65500'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:65500'
            ],
            'contributors' => [
                'nullable',
                'array',
                'max:10'
            ],
            'keywords' => [
                'nullable',
                'array',
                'max:10'
            ],
            'alternate_url' => [
                'nullable',
                'url',
                'max:255'
            ],
            'is_private' => [
                'required',
                'boolean'
            ],
            'is_explicit' => [
                'required',
                'boolean'
            ],
            'published_at' => [
                'nullable',
                'date'
            ]
        ];
    }
}
