<?php
namespace Podhost\Podstream\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;


class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'podstream:install
                                              {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Podstream models and resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        // Publish...
        $this->callSilent('vendor:publish', ['--tag' => 'podstream-config', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'podstream-migrations', '--force' => true]);

        // Directories...
        (new Filesystem)->ensureDirectoryExists(app_path('Actions/Podstream'));
        (new Filesystem)->ensureDirectoryExists(app_path('Actions/Jetstream'));

        // Service Providers...
        copy(__DIR__.'/../../stubs/app/Providers/AuthServiceProvider.php', app_path('Providers/AuthServiceProvider.php'));
        copy(__DIR__.'/../../stubs/app/Providers/PodstreamServiceProvider.php', app_path('Providers/PodstreamServiceProvider.php'));

        $this->installServiceProviderAfter('JetstreamServiceProvider', 'PodstreamServiceProvider');

        // Models...
        copy(__DIR__.'/../../stubs/app/Models/UserWithPodcasts.php', app_path('Models/User.php'));
        copy(__DIR__.'/../../stubs/app/Models/Podcast.php', app_path('Models/Podcast.php'));
        copy(__DIR__.'/../../stubs/app/Models/PodcastInvitation.php', app_path('Models/PodcastInvitation.php'));
        copy(__DIR__.'/../../stubs/app/Models/PodcastMembership.php', app_path('Models/PodcastMembership.php'));
        copy(__DIR__.'/../../stubs/app/Models/Episode.php', app_path('Models/Episode.php'));

        // Actions...
        copy(__DIR__.'/../../stubs/app/Actions/Podstream/AddPodcastMember.php', app_path('Actions/Podstream/AddPodcastManager.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Podstream/CreatePodcast.php', app_path('Actions/Podstream/CreatePodcast.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Podstream/DeletePodcast.php', app_path('Actions/Podstream/DeletePodcast.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Podstream/InvitePodcastMember.php', app_path('Actions/Podstream/InvitePodcastMember.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Podstream/RemovePodcastMember.php', app_path('Actions/Podstream/RemovePodcastMember.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Podstream/UpdatePodcast.php', app_path('Actions/Podstream/UpdatePodcast.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Podstream/CreateEpisode.php', app_path('Actions/Podstream/CreateEpisode.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Podstream/UpdateEpisode.php', app_path('Actions/Podstream/UpdateEpisode.php'));
        copy(__DIR__.'/../../stubs/app/Actions/Podstream/DeleteEpisode.php', app_path('Actions/Podstream/DeleteEpisode.php'));

        // replace Jetstream delete user with the provided one
        copy(__DIR__.'/../../stubs/app/Actions/Jetstream/DeleteUserWithPodcasts.php', app_path('Actions/Jetstream/DeleteUser.php'));

        // Policies...
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/app/Policies', app_path('Policies'));

    }


    /**
     * Install the service provider in the application configuration file.
     *
     * @param  string  $after
     * @param  string  $name
     * @return void
     */
    protected function installServiceProviderAfter($after, $name)
    {
        if (! Str::contains($appConfig = file_get_contents(config_path('app.php')), 'App\\Providers\\'.$name.'::class')) {
            file_put_contents(config_path('app.php'), str_replace(
                'App\\Providers\\'.$after.'::class,',
                'App\\Providers\\'.$after.'::class,'.PHP_EOL.'        App\\Providers\\'.$name.'::class,',
                $appConfig
            ));
        }
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
