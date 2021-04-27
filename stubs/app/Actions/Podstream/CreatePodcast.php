<?php
namespace App\Actions\Podstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Podhost\Podstream\Support\Contracts\CreatesPodcasts;
use Podhost\Podstream\Events\AddingPodcast;
use Podhost\Podstream\Podstream;

class CreatePodcast implements CreatesPodcasts
{
    /**
     * Validate and create a new podcast for the given user.
     *
     * @param $user
     * @param array $input
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create($user, array $input)
    {
        Gate::forUser($user)->authorize('create', Podstream::newPodcastModel());

        $model = Podstream::podcastModel();

        Validator::make($input, $model::validationRules() )->validateWithBag('createPodcast');

        AddingPodcast::dispatch($user);

        $user->switchPodcast($podcast = $user->ownedPodcasts()->create([
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
        ]));

        return $podcast;
    }
}
