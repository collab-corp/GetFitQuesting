<?php

if (! function_exists('create')) {
    function create($class, $attributes = [], $times = null)
    {
    	if (is_numeric($attributes) && is_null($times)) {
    		$times = $attributes;
    		$attributes = [];
    	}

        return factory($class, $times)->create($attributes);
    }
}


if (! function_exists('make')) {
    function make($class, $attributes = [], $times = null)
    {
    	if (is_numeric($attributes) && is_null($times)) {
    		$times = $attributes;
    		$attributes = [];
    	}

        return factory($class, $times)->make($attributes);
    }
}