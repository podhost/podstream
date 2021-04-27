<?php
namespace App\Actions\Podstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Podhost\Podstream\Support\Contracts\CreatesEpisodes;
use Podhost\Podstream\Events\AddingEpisode;
use Podhost\Podstream\Podstream;

class CreateEpisode implements CreatesEpisodes
{
    /**
     * Validate and create a new episode for the given podcast and user.
     *
     * @param $user
     * @param $podcast
     * @param array $input
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create($user, $podcast, array $input)
    {
        Gate::forUser($user)->authorize('createEpisode', $podcast);

        $model = Podstream::episodeModel();

        Validator::make($input, $model::validationRules() )->validateWithBag('createEpisode');

        AddingEpisode::dispatch($user);

        $episode = $podcast->episodes()->create([
            'episode_number' => $input['episode_number'],
            'season_number' => $input['season_number'],
            'title' => $input['title'],
            'slug' => $input['slug'],
            'episode_type' => $input['episode_type'],
            'summary' => $input['summary'],
            'notes' => $input['notes'],
            'contributors' => $input['contributors'],
            'keywords' => $input['keywords'],
            'alternate_url' => $input['alternate_url'],
            'is_private' => $input['is_private'],
            'is_explicit' => $input['is_explicit'],
        ]);

        return $episode;
    }
}
