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
        return !blank($value);
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
        if(isset($_ENV[$key]))
            return $_ENV[$key];

        return $default;
    }
}

if(!function_exists('config')){
    /**
     * Get values from a config file
     * 
     * @param  string $file
     * @param  string $path = ''
     * @return array
     */
    function config(string $file, string $path = '')
    {
        if($path == ''){
            if(defined('LARAPRESS_PATH')){
                $path = LARAPRESS_PATH . '/config/';
            }
        }

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

if(!function_exists('path')){
    /**
     * Transform path for Windows
     * 
     * @param  string $path
     * @return string transformed_path
     */
    function path(string $path)
    {
        if(PHP_OS_FAMILY == 'Windows')
            return str_replace("/", "\\", $path);

        return $path;
    }
}

if(!function_exists('view')){
    /**
     * Get html template from views folder
     *
     * @param string $file
     * @param array $data
     * @param boolean $return
     * @return void
     */
    function view(string $file, array $data = [], bool $return = false){
        if(!defined('LARAPRESS_PATH'))
            return;

        $filePath = LARAPRESS_PATH . '/resources/views/' . $file . '.php';

        if(!file_exists($filePath))
            return;

        if(is_array($data) && !empty($data))
            extract($data);
            
        unset($data);

        ob_start();
        include($filePath);
        $content = ob_get_contents();
        ob_end_clean();

        if($return)
            return $content;

        echo $content;
    }
}