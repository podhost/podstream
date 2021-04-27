<?php
namespace App\Actions\Podstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Podhost\Podstream\Podstream;
use Podhost\Podstream\Support\Contracts\UpdatesPodcast;

class UpdatePodcast implements UpdatesPodcast
{
    /**
     * Validate and update the given podcast.
     *
     * @param $user
     * @param $podcast
     * @param array $input
     * @return mixed|void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update($user, $podcast, array $input)
    {
        Gate::forUser($user)->authorize('update', $podcast);

        $model = Podstream::podcastModel();

        Validator::make($input, $model::validationRules())->validateWithBag('updatePodcast');

        $podcast->forceFill([
            'title' => $input['title'],
            'description' => $input['description'],
            'type' => $input['type'],
            'timezone' => $input['timezone'],
            'language' => $input['language'],
            'is_mp3_download_allowed' => $input['is_mp3_download_allowed'],
            'is_explicit' => $input['is_explicit'],
            'tags' => $input['tags'],
            'authors' => $input['authors'],
            'owner' => $input['owner'],
            'owner_email' => $input['owner_email'],
            'copyright' => $input['copyright'],
        ])->save();
    }
}
