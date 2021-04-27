<?php

namespace Podhost\Podstream;

use Illuminate\Support\Arr;
use Podhost\Podstream\Support\Contracts\AddsPodcastMembers;
use Podhost\Podstream\Support\Contracts\CreatesEpisodes;
use Podhost\Podstream\Support\Contracts\CreatesPodcasts;
use Podhost\Podstream\Support\Contracts\DeletesEpisodes;
use Podhost\Podstream\Support\Contracts\DeletesPodcasts;
use Podhost\Podstream\Support\Contracts\InvitesPodcastMembers;
use Podhost\Podstream\Support\Contracts\RemovesPodcastMembers;
use Podhost\Podstream\Support\Contracts\UpdatesEpisodes;
use Podhost\Podstream\Support\Contracts\UpdatesPodcast;

class Podstream
{

    /**
     * The roles that are available to assign to users.
     *
     * @var array
     */
    public static $roles = [];


    /**
     * The permissions that exist within the application.
     *
     * @var array
     */
    public static $permissions = [];

    /**
     * The default permissions that should be available to new entities.
     *
     * @var array
     */
    public static $defaultPermissions = [];


    /**
     * The user model that should be used by Podstream.
     *
     * @var string
     */
    public static $userModel = 'App\\Models\\User';


    /**
     * The podcast model that should be used by Podstream.
     *
     * @var string
     */
    public static $podcastModel = 'App\\Models\\Podcast';


    /**
     * The membership model that should be used by Podstream.
     *
     * @var string
     */
    public static $membershipModel = 'App\\Models\\PodcastMembership';


    /**
     * The podcast invitation model that should be used by Podstream.
     *
     * @var string
     */
    public static $podcastInvitationModel = 'App\\Models\\PodcastInvitation';


    /**
     * The episode model used by Podstream.
     *
     * @var string
     */
    public static $episodeModel = 'App\\Models\\Episode';


    /**
     * Determine if Podstream has registered roles.
     *
     * @return bool
     */
    public static function hasRoles()
    {
        return count(static::$roles) > 0;
    }


    /**
     * Find the role with the given key.
     *
     * @param  string  $key
     * @return \Podhost\Podstream\Role
     */
    public static function findRole(string $key)
    {
        return static::$roles[$key] ?? null;
    }


    /**
     * Define a role.
     *
     * @param  string  $key
     * @param  string  $name
     * @param  array  $permissions
     * @return \Podhost\Podstream\Role
     */
    public static function role(string $key, string $name, array $permissions)
    {
        static::$permissions = collect(array_merge(static::$permissions, $permissions))
            ->unique()
            ->sort()
            ->values()
            ->all();

        return tap(new Role($key, $name, $permissions), function ($role) use ($key) {
            static::$roles[$key] = $role;
        });
    }

    /**
     * Determine if any permissions have been registered with Podstream.
     *
     * @return bool
     */
    public static function hasPermissions()
    {
        return count(static::$permissions) > 0;
    }


    /**
     * Define the available API token permissions.
     *
     * @param  array  $permissions
     * @return static
     */
    public static function permissions(array $permissions)
    {
        static::$permissions = $permissions;

        return new static;
    }


    /**
     * Define the default permissions that should be available to new API tokens.
     *
     * @param  array  $permissions
     * @return static
     */
    public static function defaultApiTokenPermissions(array $permissions)
    {
        static::$defaultPermissions = $permissions;

        return new static;
    }


    /**
     * Return the permissions in the given list that are actually defined permissions for the application.
     *
     * @param  array  $permissions
     * @return array
     */
    public static function validPermissions(array $permissions)
    {
        return array_values(array_intersect($permissions, static::$permissions));
    }

    /**
     * Find a user instance by the given ID.
     *
     * @param  int  $id
     * @return mixed
     */
    public static function findUserByIdOrFail($id)
    {
        return static::newUserModel()->where('id', $id)->firstOrFail();
    }

    /**
     * Find a user instance by the given email address or fail.
     *
     * @param  string  $email
     * @return mixed
     */
    public static function findUserByEmailOrFail(string $email)
    {
        return static::newUserModel()->where('email', $email)->firstOrFail();
    }

    /**
     * Get the name of the user model used by the application.
     *
     * @return string
     */
    public static function userModel()
    {
        return static::$userModel;
    }

    /**
     * Get a new instance of the user model.
     *
     * @return mixed
     */
    public static function newUserModel()
    {
        $model = static::userModel();

        return new $model;
    }


    /**
     * Specify the user model that should be used by Podstream.
     *
     * @param  string  $model
     * @return static
     */
    public static function useUserModel(string $model)
    {
        static::$userModel = $model;

        return new static;
    }


    /**
     * Get the name of the episode model used by the application.
     *
     * @return string
     */
    public static function episodeModel()
    {
        return static::$episodeModel;
    }

    /**
     * Get a new instance of the episode model.
     *
     * @return mixed
     */
    public static function newEpisodeModel()
    {
        $model = static::$episodeModel;

        return new $model;
    }

    /**
     * Specify the episode model that should be used by Podstream.
     *
     * @param  string  $model
     * @return static
     */
    public static function useEpisodeModel(string $model)
    {
        static::$episodeModel = $model;

        return new static;
    }


    /**
     * Get the name of the podcast model used by the application.
     *
     * @return string
     */
    public static function podcastModel()
    {
        return static::$podcastModel;
    }


    /**
     * Get a new instance of the podcast model.
     *
     * @return mixed
     */
    public static function newPodcastModel()
    {
        $model = static::podcastModel();

        return new $model;
    }

    /**
     * Specify the podcast model that should be used by Podstream.
     *
     * @param  string  $model
     * @return static
     */
    public static function usePodcastModel(string $model)
    {
        static::$podcastModel = $model;

        return new static;
    }

    /**
     * Get the name of the membership model used by the application.
     *
     * @return string
     */
    public static function membershipModel()
    {
        return static::$membershipModel;
    }

    /**
     * Specify the membership model that should be used by Podstream.
     *
     * @param  string  $model
     * @return static
     */
    public static function useMembershipModel(string $model)
    {
        static::$membershipModel = $model;

        return new static;
    }


    /**
     * Get the name of the podcast invitation model used by the application.
     *
     * @return string
     */
    public static function podcastInvitationModel()
    {
        return static::$podcastInvitationModel;
    }


    /**
     * Specify the podcast invitation model that should be used by Podstream.
     *
     * @param  string  $model
     * @return static
     */
    public static function usePodcastInvitationModel(string $model)
    {
        static::$podcastInvitationModel = $model;

        return new static;
    }

    /**
     * Register a class / callback that should be used to create podcasts.
     *
     * @param  string  $class
     * @return void
     */
    public static function createPodcastsUsing(string $class)
    {
        return app()->singleton(CreatesPodcasts::class, $class);
    }

    /**
     * Register a class / callback that should be used to update podcasts.
     *
     * @param  string  $class
     * @return void
     */
    public static function updatePodcastsUsing(string $class)
    {
        return app()->singleton(UpdatesPodcast::class, $class);
    }


    /**
     * Register a class / callback that should be used to add podcast members.
     *
     * @param  string  $class
     * @return void
     */
    public static function addPodcastMembersUsing(string $class)
    {
        return app()->singleton(AddsPodcastMembers::class, $class);
    }

    /**
     * Register a class / callback that should be used to add podcast members.
     *
     * @param  string  $class
     * @return void
     */
    public static function invitePodcastMembersUsing(string $class)
    {
        return app()->singleton(InvitesPodcastMembers::class, $class);
    }


    /**
     * Register a class / callback that should be used to remove podcast members.
     *
     * @param  string  $class
     * @return void
     */
    public static function removePodcastsMembersUsing(string $class)
    {
        return app()->singleton(RemovesPodcastMembers::class, $class);
    }



    /**
     * Register a class / callback that should be used to delete podcasts.
     *
     * @param  string  $class
     * @return void
     */
    public static function deletePodcastsUsing(string $class)
    {
        return app()->singleton(DeletesPodcasts::class, $class);
    }


    /**
     * Register a class / callback that should be used to create episodes.
     *
     * @param string $class
     */
    public static function createEpisodesUsing(string $class)
    {
        return app()->singleton(CreatesEpisodes::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete episodes.
     *
     * @param string $class
     */
    public static function deleteEpisodesUsing(string $class)
    {
        return app()->singleton(DeletesEpisodes::class, $class);
    }


    /**
     * Register a class / callback that should be used to update episodes.
     *
     * @param string $class
     */
    public static function updateEpisodesUsing(string $class)
    {
        return app()->singleton(UpdatesEpisodes::class, $class);
    }


    /**
     * Find the path to a localized Markdown resource.
     *
     * @param  string  $name
     * @return string|null
     */
    public static function localizedMarkdownPath($name)
    {
        $localName = preg_replace('#(\.md)$#i', '.'.app()->getLocale().'$1', $name);

        return Arr::first([
            resource_path('markdown/'.$localName),
            resource_path('markdown/'.$name),
        ], function ($path) {
            return file_exists($path);
        });
    }

}
