<?php
namespace Podhost\Podstream\Rules;

use Illuminate\Contracts\Validation\Rule;

class EpisodeType implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, config('podstream.episode_types'));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :attribute must be a valid episode type.');
    }
}
