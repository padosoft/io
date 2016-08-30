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
        if ($paths === null || $paths == '') {
            return false;
        }
        if ($needle === null || $needle == '') {
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
        if ($paths === null || $paths == '') {
            return false;
        }
        if ($needle === null || $needle == '') {
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
            $pathPattern = base_path() . '/*';
        } elseif ($pathPattern === null || $pathPattern == '') {
            $pathPattern = __DIR__ . '/*';
        } elseif (!self::endsWithStar($pathPattern)) {
            $pathPattern = DirHelper::addFinalSlash($pathPattern);
        }

        $files = glob($pathPattern, GLOB_ONLYDIR);

        foreach (glob(dirname($pathPattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, self::findDirs($dir . '/' . basename($pathPattern)));
        }

        return $files;
    }

    /**
     * Dir::Delete()
     * get a dir path and remove all files and subdir in this dir.
     * if $not_remove_dir==TRUE then when finish DO NOT REMOVE THE $directory dir.
     * @param string $directory directory to empty
     * @param bool $not_remove_dir TRUE if DO NOT REMOVE THE $directory dir but only files.
     * @return bool true if success, otherwise false
     **/
    public static function delete($directory, bool $not_remove_dir = false) : bool
    {
        $directory = self::removeFinalSlash($directory);

        if (!self::isDirSafe($directory) || !is_readable($directory)) {
            return false;
        }

        $directoryHandle = opendir($directory);
        while (false !== ($contents = readdir($directoryHandle))) {
            if ($contents == '.' || $contents == '..') {
                continue;
            }
            $path = $directory . "/" . $contents;

            if (is_dir($path)) {
                self::delete($path, $not_remove_dir);
            } else {
                unlink($path);
            }
        }
        closedir($directoryHandle);

        if (!$not_remove_dir) {
            return true;
        }
        return rmdir($directory);
    }

    /**
     * Remove final slash ('/') char in dir if ends with slash.
     * @param $directory
     * @return string
     */
    public static function removeFinalSlash($directory) : string
    {
        if (self::endsWithSlash($directory)) {
            $directory = substr($directory, 0, -1);
        }
        return $directory;
    }

    /**
     * For each dir passed in array, check if not finishes with '/' otherwise remove a slash to path.
     * If not dir, leave the element untouched.
     * @param array $paths
     * @return array
     */
    public static function removeFinalSlashToAllPaths(array $paths) : array
    {
        if (empty($paths)) {
            return [];
        }

        return array_map('self::removeFinalSlash', $paths);
    }

    /**
     * Dir::copy()
     * Copy a source directory (files and all subdirectories) to destination directory.
     * If Destination directory doesn't exists try to create it.
     * @param $directorySource
     * @param $directoryDestination
     * @param array $excludedDirectory array of path to be escluded (i.e. it will not copied to destination folder)
     * @param \Closure|null $copied a function with two arguments  ($directorySource,$directoryDestination).
     * @return bool true if success, otherwise false
     */
    public static function copy(
        $directorySource,
        $directoryDestination,
        array $excludedDirectory = [],
        \Closure $copied = null
    ) : bool
    {
        $directorySource = self::removeFinalSlash($directorySource);
        if (!self::isDirSafe($directorySource) || !is_readable($directorySource)) {
            return false;
        }

        $directoryDestination = self::removeFinalSlash($directoryDestination);
        if (!self::checkDirExistOrCreate($directoryDestination)) {
            return false;
        }
        is_callable($copied) ? $copied($directorySource, $directoryDestination) : '';

        $excludedDirectory = self::removeFinalSlashToAllPaths($excludedDirectory);

        $directorySourceHandle = opendir($directorySource);
        while (false !== ($contents = readdir($directorySourceHandle))) {
            if ($contents == '.' || $contents == '..') {
                continue;
            }
            $path = $directorySource . "/" . $contents;
            if (in_array(DirHelper::removeFinalSlash($path), $excludedDirectory)) {
                continue;
            }
            $pathDest = $directoryDestination . "/" . $contents;

            if (is_dir($path)) {
                self::copy($path, $pathDest, $excludedDirectory);
            } else {
                copy($path, $pathDest);
                is_callable($copied) ? $copied($path, $pathDest) : '';
            }
        }
        closedir($directorySourceHandle);
        return true;
    }
}
