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
     * @param  string $key
     * @param  string $default = null
     * @return string
     */
    function env($key, $default = null)
    {
        if($env = getenv($key))
            return $env;

        return $default;
    }
}

if(!function_exists('config')){
    /**
     * Get values from a config file
     * 
     * @param  string $value
     * @param  string $path = null
     * @return string
     */
    function config($file, $path = null)
    {
        if(!is_string($file))
            return;

        if(is_null($path))
            $path = env('CONFIG_PATH');

        if(!is_string($path))
            return;

        $configFile = $path . $file . '.php';
        if(file_exists($configFile)){
            $array = include($configFile);

            if(!is_array($array))
                return;

            return $array;
        }
    }
}