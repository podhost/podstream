<?php
namespace App\Actions\Podstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Podhost\Podstream\Podstream;
use Podhost\Podstream\Support\Contracts\UpdatesEpisodes;

class UpdateEpisode implements UpdatesEpisodes
{
    /**
     * Validate and update the given episode.
     *
     * @param $user
     * @param $episode
     * @param array $input
     * @return mixed|void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update($user, $episode, array $input)
    {
        Gate::forUser($user)->authorize('update', $episode);

        $model = Podstream::episodeModel();

        Validator::make($input, $model::validationRules())->validateWithBag('updateEpisode');

        $episode->forceFill([
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
        ])->save();
    }
}
