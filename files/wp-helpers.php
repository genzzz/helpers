<?php

if(!function_exists('wp_assets')){
    /**
     * Get wordpress assets url
     *
     * @return string
     */
    function wp_assets(){
        return site_url('/assets');
    }
}

if(!function_exists('wp_theme_assets')){
    /**
     * Get current theme assets folder
     *
     * @uses wp_assets()
     * @return string
     */
    function wp_theme_assets(){
        $theme = wp_get_theme();
        return wp_assets() . '/themes/' . $theme->Stylesheet;
    }
}

if(!function_exists('wp_theme_version')){
    /**
     * Get current theme version
     *
     * @return string
     */
    function wp_theme_version(){
        $theme = wp_get_theme();
        return $theme->Version;
    }
}