<?php
namespace Bladerunner;

/**
 * Handles cache functionalities.
 */
class Cache
{
    /**
     * Add WordPress hooks.
     */
    public function __construct()
    {
        add_action('save_post', '\Bladerunner\Cache::remove_all_views');
    }

    /**
     * Return true if compilation expired
     */
    public static function expired($blade, $view, $path)
    {
        $result = false;

        $wp_debug = defined('WP_DEBUG') && WP_DEBUG;

        $result = $wp_debug || $result;

        $result = (!file_exists($path)) || $result;

        $result = $blade->getCompiler()->isExpired($view->getPath()) || $result;

        return $result;
    }

    /**
     * Gets the cache folder for Bladerunner.
     */
    public static function path()
    {
        $result = wp_upload_dir()['basedir'];
        $result .= '/.cache';

        return apply_filters('bladerunner/cache_path', $result);
    }

    /**
     * Remove all views in cache folder
     */
    public static function remove_all_views()
    {
        $dir = Cache::path();
        $files = array_diff(scandir($dir, 1), ['.','..']);
        foreach ($files as $file) {
            @unlink("$dir/$file");
        }
    }
}