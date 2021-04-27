<?php
namespace Podhost\Podstream\Support\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class ModelHasUuid {

    use Uuid;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
