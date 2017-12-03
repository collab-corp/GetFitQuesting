<?php

use Carbon\CarbonInterval;
use Illuminate\Container\BoundMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;

if (! function_exists('faker')) {
    /**
     * Get an instance of the Faker Generator.
     *
     * @return \Faker\Generator
     */
    function faker()
    {
        return resolve(\Faker\Generator::class);
    }
}

if (!function_exists('fluent')) {
    /**
     * Get a Fluent instance.
     *
     * @param  mixed $value
     * @return \Illuminate\Support\Fluent
     */
    function fluent($value)
    {
        return new Fluent($value);
    }
}

if (! function_exists('is_timestamp')) {
    /**
     * Determine if given value is a valid unix timestamp
     *
     * @param  string|integer  $timestamp
     * @return boolean
     */
    function is_timestamp($timestamp)
    {
        if (is_numeric($timestamp) && ($timestamp <= PHP_INT_MAX) && ($timestamp >= ~PHP_INT_MAX)) {
            try {
                new \DateTime($timestamp);
            } catch (\Exception $e) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('is_date')) {
    /**
     * Validate that an attribute is a valid date.
     *
     * @param  mixed $value
     * @return bool
     */
    function is_date($value)
    {
        if ($value instanceof DateTime) {
            return true;
        }

        if ((!is_string($value) && !is_numeric($value)) || strtotime($value) === false) {
            return false;
        }

        $date = date_parse($value);

        return checkdate($date['month'], $date['day'], $date['year']);
    }
}

if (!function_exists('carbon')) {
    /**
     * Returns a Carbon instance for given timestamp.
     *
     * @param dynamic
     *
     * @return Carbon
     */
    function carbon()
    {
        list($date, $time, $timezone) = array_pad(func_get_args(), 3, null);

        if ($date instanceof \DateTime) {
            return $date;
        }

        if (str_contains($date, ':')) {
            return Carbon::parse($date, $timezone);
        }

        if ($date instanceof Model) {
            $date = $date->{$date->getCreatedAtColumn()};
        }

        if (is_string($time) && ! str_contains($time, ':')) {
            $timezone = $time;
            $time = null;
        }

        return tap(Carbon::parse($date, $timezone), function ($carbon) use ($time) {
            if ($time) {
                $carbon->setTimeFromTimestring($time);
            }
        });
    }
}

if (!function_exists('yesterday')) {
    /**
     * Return a Carbon instance for yesterday.
     *
     * @param string | \DateTimeZone $timezone
     *
     * @return \Carbon\Carbon
     */
    function yesterday($timezone = null)
    {
        return Carbon::yesterday($timezone)->startOfDay();
    }
}

if (!function_exists('tomorrow')) {
    /**
     * Return a Carbon instance for tomorrow.
     *
     * @param string | \DateTimeZone $timezone
     *
     * @return \Carbon\Carbon
     */
    function tomorrow($timezone = null)
    {
        return Carbon::tomorrow($timezone)->startOfDay();
    }
}

if (!function_exists('epoch')) {
    /**
     * Get a Carbon instance of the beginning of time.
     *
     * @param string | null $time
     * @return \Carbon\Carbon
     */
    function epoch($time = null)
    {
        return carbon('1970-01-01', null, $time);
    }
}

if (! function_exists('interval')) {
    /**
     * Get a time interval.
     *
     * @param  int    $period
     * @param  string $metric
     * @return \Carbon\CarbonInterval
     */
    function interval(int $period, string $metric = 'minutes')
    {
        $metric = str_plural($metric);

        return CarbonInterval::$metric($period);
    }
}

if (!function_exists('is_email')) {
    /**
     * Determines whether an email was given.
     *
     * @param  mixed $email
     * @return boolean
     */
    function is_email($email)
    {
        return is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

if (!function_exists('when')) {

    /**
     * When given value is truthy,
     * call the given callback or proxy the value.
     *
     * @param mixed         $value
     * @param callable|null $callback
     *
     * @return mixed
     */
    function when($value, callable $callback = null)
    {
        if ($value && $callback) {
            return BoundMethod::call($callback, ['value' => $value]);
        }

        return $value;
    }
}

if (!function_exists('str_lower')) {
    /**
     * Lowercase a string
     *
     * @param string $value
     *
     * @return string
     */
    function str_lower($value)
    {
        return Str::lower($value);
    }
}

if (!function_exists('relation_exists')) {
    /**
     * Determine whether a relation exists.
     * 
     * @param  Model  $model   
     * @param  string $relation
     * @return boolean
     */
    function relation_exists(Model $model, string $relation)
    {
        return method_exists($model, $relation) 
        && $model->$relation() instanceof Relation;
    }
}

if (!function_exists('relations_exists')) {
    /**
     * Determine whether given relations exists.
     * 
     * @param  Model  $model   
     * @param  string[] $relations
     * @return boolean
     */
    function relations_exists(Model $model, ...$relations)
    {
        foreach (array_wrap(...$relations) as $relation) {
            if (relation_exists($model, $relation)) {
                continue;
            }

            return false;
        }

        return true;
    }
}