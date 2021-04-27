<?php

return [

    /*
      |--------------------------------------------------------------------------
      | Podstream Route Middleware
      |--------------------------------------------------------------------------
      |
      | Here you may specify which middleware Podstream will assign to the routes
      | that it registers with the application. When necessary, you may modify
      | these middleware; however, this default value is usually sufficient.
      |
      */
    'middleware' => ['web'],


    /*
     |--------------------------------------------------------------------------
     | Podstream Podcast types
     |--------------------------------------------------------------------------
     |
     | Here you may specify the Podcast types used to create database enum field
     |
     */
    'podcast_types' => ['episodic', 'serial', 'episodic_with_seasons'],

    /*
     |--------------------------------------------------------------------------
     | Podstream Episode types
     |--------------------------------------------------------------------------
     |
     | Here you may specify the Episode types used to create database enum field
     |
     */
    'episode_types' => ['full', 'trailer', 'bonus'],

    /*
      |--------------------------------------------------------------------------
      | Podstream Available Locales
      |--------------------------------------------------------------------------
      |
      | Podcast require list of languages
      |
     */
    'available_locales' => [
        'en' => 'English'
    ],


];
