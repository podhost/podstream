<?php

namespace App\Providers;

use App\Models\Episode;
use App\Models\Podcast;
use App\Policies\EpisodePolicy;
use App\Policies\PodcastPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Podcast::class => PodcastPolicy::class,
        Episode::class => EpisodePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
