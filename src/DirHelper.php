<?php

namespace Padosoft\Io;

/**
 * Helper Class DirHelper
 * @package Padosoft\Io
 */
class DirHelper
{
    /**
     * Check if passed path exists or not.
     * @param string $filePath
     * @return bool
     */
    public static function isDirSafe(string $filePath) : bool
    {
        if (!$filePath) {
            return false;
        }

        return is_dir($filePath);
    }

    /**
     * Check if passed path exists or try to create it.
     * Return false if it fails to create it or if a file (and not a dir) passed as argument.
     * @param string $filePath
     * @param string $modeMask default '0755'
     * @return bool
     */
    public static function checkDirExistOrCreate(string $filePath, string $modeMask = '0755') : bool
    {
        if (self::isDirSafe($filePath)) {
            return true;
        }

        //controllo adesso che non sia un file
        if (FileHelper::fileExistsSafe($filePath)) {
            return false;
        }

        return mkdir($filePath, $modeMask, true) && self::isDirSafe($filePath);
    }

    /**
     * If dir passed, check if finishes with '/' otherwise append a slash to path.
     * If wrong or empty string passed, return '/'.
     * @param string $path
     * @return string
     */
    public static function addFinalSlash(string $path) : string
    {
        if ($path === null || $path == '') {
            return '/';
        }

        $quoted = preg_quote('/', '/');
        $path = preg_replace('/(?:' . $quoted . ')+$/', '', $path) . '/';

        return $path;
    }

    /**
     * for each dir passed in array, check if it finishes with '/' otherwise append a slash to path.
     * If not dir, leave the element untouched.
     * @param array $paths
     * @return array
     */
    public static function addFinalSlashToAllPaths(array $paths) : array
    {
        if (empty($paths)) {
            return [];
        }

        return array_map('self::addFinalSlash', $paths);
    }

    /**
     * Check if path ends with slash '/'
     * @param string $paths
     * @return bool
     */
    public static function endsWithSlash(string $paths) : bool
    {
        return self::endsWith($paths, '/');
    }

    /**
     * Check if path ends with star '*'
     * @param string $paths
     * @return bool
     */
    public static function endsWithStar(string $paths) : bool
    {
        return self::endsWith($paths, '*');
    }

    /**
     * Check if path ends with $needle
     * @param string $paths
     * @param string $needle
     * @return bool
     */
    public static function endsWith(string $paths, string $needle) : bool
    {
        if ($paths===null || $paths=='') {
            return false;
        }
        if ($needle===null || $needle=='') {
            return false;
        }

        // search forward starting from end minus needle length characters
        // see: http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
        return (($temp = strlen($paths) - strlen($needle)) >= 0 && strpos($paths, $needle, $temp) !== false);
    }

    /**
     * Check if path starts with slash '/'
     * @param string $paths
     * @return bool
     */
    public static function startsWithSlash(string $paths) : bool
    {
        return self::startsWith($paths, '/');
    }

    /**
     * Check if path starts with slash $needle
     * @param string $paths
     * @param string $needle
     * @return bool
     */
    public static function startsWith(string $paths, string $needle) : bool
    {
        if ($paths===null || $paths=='') {
            return false;
        }
        if ($needle===null || $needle=='') {
            return false;
        }

        // search backwards starting from haystack length characters from the end
        // see: http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php
        return strrpos($paths, $needle, -strlen($paths)) !== false;
    }

    /**
     * Find dirs matching a pattern (recursive with subdirs).
     * Returns an array containing the matched dirs (full path and not files),
     * an empty array if no dir matched or on error.
     * @param string $pathPattern if is null it set to base_path()/* if exists otherwise __DIR__/*. It support glob() string pattern.
     * @return array of dirs
     */
    public static function findDirs(string $pathPattern)
    {
        if (($pathPattern === null || $pathPattern == '') && function_exists('base_path')) {
            $pathPattern = base_path().'/*';
        }elseif ($pathPattern === null || $pathPattern == '') {
            $pathPattern = __DIR__.'/*';
        }elseif(!self::endsWithStar($pathPattern)){
            $pathPattern = DirHelper::addFinalSlash($pathPattern);
        }

        $files = glob($pathPattern, GLOB_ONLYDIR);

        foreach (glob(dirname($pathPattern).'/*',GLOB_ONLYDIR|GLOB_NOSORT) as $dir){
            $files = array_merge($files, self::findDirs($dir.'/'.basename($pathPattern), GLOB_ONLYDIR));
        }

        return $files;
    }
}
