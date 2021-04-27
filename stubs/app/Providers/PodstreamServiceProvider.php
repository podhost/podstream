<?php
namespace App\Providers;

use App\Actions\Podstream\AddPodcastMember;
use App\Actions\Podstream\CreateEpisode;
use App\Actions\Podstream\CreatePodcast;
use App\Actions\Podstream\DeleteEpisode;
use App\Actions\Podstream\DeletePodcast;
use App\Actions\Podstream\InvitePodcastMember;
use App\Actions\Podstream\RemovePodcastMember;
use App\Actions\Podstream\UpdateEpisode;
use App\Actions\Podstream\UpdatePodcast;
use Illuminate\Support\ServiceProvider;
use Podhost\Podstream\Podstream;

class PodstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Podstream::createPodcastsUsing(CreatePodcast::class);
        Podstream::updatePodcastsUsing(UpdatePodcast::class);
        Podstream::addPodcastMembersUsing(AddPodcastMember::class);
        Podstream::invitePodcastMembersUsing(InvitePodcastMember::class);
        Podstream::removePodcastsMembersUsing(RemovePodcastMember::class);
        Podstream::deletePodcastsUsing(DeletePodcast::class);
        Podstream::createEpisodesUsing(CreateEpisode::class);
        Podstream::updateEpisodesUsing(UpdateEpisode::class);
        Podstream::deleteEpisodesUsing(DeleteEpisode::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Podstream::role('admin', __('Administrator'), [
            'podcast:update',
            'manager:create',
            'manager:update',
            'episode:create',
            'episode:update',
            'episode:delete',
        ])->description(__('Administrator users can perform any action.'));

        Podstream::role('manager', __('Manager'), [
            'episode:create',
            'episode:update',
        ])->description(__('Editor users have the ability to read, create, and update.'));

        Podstream::role('analytics', __('Analytics'), [
        ])->description(__('Analytics users have the ability to read podcast analytics only.'));

    }
}
