<?php

if(!function_exists('blank')){
    /**
     * Determine if the given value is "blank".
     *
     * @param  mixed  $value
     * @return bool
     */
    function blank($value)
    {
        if (is_null($value)) {
            return true;
        }
        if (is_string($value)) {
            return trim($value) === '';
        }
        if (is_numeric($value) || is_bool($value)) {
            return false;
        }
        if ($value instanceof Countable) {
            return count($value) === 0;
        }
        return empty($value);
    }
}

if(!function_exists('filled')){
    /**
     * Determine if a value is "filled".
     *
     * @param  mixed  $value
     * @return bool
     */
    function filled($value)
    {
        return ! blank($value);
    }
}

if(!function_exists('env')){
    /**
     * Get env values or default
     * 
     * @param string $key
     * @param string $default = null
     * @return string
     */
    function env($key, $default = null)
    {
        if($env = getenv($key))
            return $env;

        return $default;
    }
}