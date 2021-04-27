<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Podhost\Podstream\Events\PodcastCreated;
use Podhost\Podstream\Events\PodcastDeleted;
use Podhost\Podstream\Events\PodcastUpdated;
use Podhost\Podstream\Podcast as PodstreamPodcast;

class Podcast extends PodstreamPodcast
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_mp3_download_allowed' => 'boolean',
        'is_explicit' => 'boolean',
        'tags' => 'array',
        'authors' => 'array'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => PodcastCreated::class,
        'updated' => PodcastUpdated::class,
        'deleted' => PodcastDeleted::class,
    ];


    /**
     * Single source of truth for validation rules
     *
     * @return array
     */
    public static function validationRules()
    {
        return [
            'title' => [
                'required',
                'string',
                'max:2000'
            ],
            'description' => [
                'nullable',
                'string',
                'max:65500',
            ],
            'type' => [
                'required',
                'in:' . implode(config('podstream.podcast_types'))
            ],
            'timezone' => [
                'required',
                'string',
                'max:255'
            ],
            'language' => [
                'required',
                'string',
                'max:10'
            ],
            'is_mp3_download_allowed' => [
                'required',
                'boolean'
            ],
            'is_explicit' => [
                'required',
                'boolean'
            ],
            'tags' => [
                'nullable',
                'array'
            ],
            'authors' => [
                'required',
                'min:1',
                'array'
            ],
            'owner' => [
                'required',
                'string',
                'max:255'
            ],
            'owner_email' => [
                'required',
                'email',
                'max:255'
            ],
            'copyright' => [
                'nullable',
                'string',
                'max:255'
            ],
        ];
    }
}
