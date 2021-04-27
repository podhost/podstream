<?php
namespace Podhost\Podstream;

use Illuminate\Support\ServiceProvider;

class PodstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/podstream.php', 'podstream');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'podstream');

        $this->configurePublishing();
        $this->configureCommands();
    }


    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/podstream.php' => config_path('podstream.php'),
        ], 'podstream-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/podstream'),
        ], 'podstream-views');


        $this->publishes([
            __DIR__.'/../database/migrations/2021_03_21_100000_create_podcasts_table.php' => database_path('migrations/2021_03_21_100000_create_podcasts_table.php'),
            __DIR__.'/../database/migrations/2021_03_21_200000_create_podcast_user_table.php' => database_path('migrations/2021_03_21_200000_create_podcast_user_table.php'),
            __DIR__.'/../database/migrations/2021_03_21_300000_create_podcast_invitations_table.php' => database_path('migrations/2021_03_21_300000_create_podcast_invitations_table.php'),
            __DIR__.'/../database/migrations/2021_03_21_400000_add_current_podcast_id_to_users_table.php' => database_path('migrations/2021_03_21_400000_add_current_podcast_id_to_users_table.php'),
            __DIR__.'/../database/migrations/2021_03_21_500000_create_episodes_table.php' => database_path('migrations/2021_03_21_500000_create_episodes_table.php'),
        ], 'podstream-podcast-migrations');
    }

    /**
     * Configure the commands offered by the application.
     *
     * @return void
     */
    protected function configureCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }


}

