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
        if ($paths == '') {
            return false;
        }
        if ($needle == '') {
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
        if ($paths == '') {
            return false;
        }
        if ($needle == '') {
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
            if (self::isDotDir($contents)) {
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
     * Remove start slash ('/') char in dir if starts with slash.
     * @param string $directory
     * @return string
     */
    public static function removeStartSlash($directory) : string
    {
        if (self::startsWithSlash($directory)) {
            $directory = substr($directory, 1);
        }
        return $directory;
    }

    /**
     * For each dir passed in array, check if not started with '/' otherwise remove a slash to path.
     * If not dir, leave the element untouched.
     * @param array $paths
     * @return array
     */
    public static function removeStartSlashToAllPaths(array $paths) : array
    {
        if (empty($paths)) {
            return [];
        }

        return array_map('self::removeStartSlash', $paths);
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
        if (!self::isReadable($directorySource)) {
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
            if (self::isDotDir($contents)) {
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

    /**
     * Returns whether the given path is on the local filesystem.
     *
     * @param string $path A path string
     *
     * @return boolean Returns true if the path is local, false for a URL
     * @see https://github.com/laradic/support/blob/master/src/Path.php
     */
    public static function isLocal($path)
    {
        return is_string($path) && '' !== $path && false === strpos($path, '://');
    }

    /**
     * Returns whether a path is absolute Unix path.
     *
     * @param string $path A path string
     *
     * @return boolean Returns true if the path is absolute unix path, false if it is
     *                 relative or empty
     */
    public static function isAbsoluteUnix($path)
    {
        return '' !== $path && '/' === $path[0];
    }

    /**
     * Returns whether a path is absolute Windows Path.
     *
     * @param string $path A path string
     *
     * @return boolean Returns true if the path is absolute, false if it is
     *                 relative or empty
     * @see https://github.com/laradic/support/blob/master/src/Path.php
     */
    public static function isAbsoluteWindows($path)
    {
        if ('' === $path) {
            return false;
        }
        if ('\\' === $path[0]) {
            return true;
        }
        // Windows root
        return self::isAbsoluteWindowsRoot($path);
    }

    /**
     * Check if win special drive C: or Normal win drive C:/  C:\
     * @param string $path
     * @return bool
     */
    protected static function isAbsoluteWindowsRoot($path):bool
    {
        if (strlen($path) > 1 && ctype_alpha($path[0]) && ':' === $path[1]) {
            // Special win drive C:
            if (2 === strlen($path)) {
                return true;
            }
            // Normal win drive C:/  C:\
            if ('/' === $path[2] || '\\' === $path[2]) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns whether a path is absolute.
     *
     * @param string $path A path string
     *
     * @return boolean Returns true if the path is absolute, false if it is
     *                 relative or empty
     */
    public static function isAbsolute($path)
    {
        return self::isAbsoluteUnix($path) || self::isAbsoluteWindows($path);
    }

    /**
     * Returns whether a path is relative.
     *
     * @param string $path A path string
     *
     * @return boolean Returns true if the path is relative or empty, false if
     *                 it is absolute
     * @see https://github.com/laradic/support/blob/master/src/Path.php
     */
    public static function isRelative($path)
    {
        return !self::isAbsolute($path);
    }

    /**
     * Joins a split file system path.
     *
     * @param  array|string
     *
     * @return string
     * @see https://github.com/laradic/support/blob/master/src/Path.php
     */
    public static function join() : string
    {
        $paths = func_get_args();
        if (func_num_args() === 1 && is_array($paths[0])) {
            $paths = $paths[0];
        }
        foreach ($paths as $key => &$argument) {
            if (is_array($argument)) {
                $argument = self::join($argument);
            }
            $argument = self::removeFinalSlash($argument);
            if ($key > 0) {
                $argument = self::removeStartSlash($argument);
            }
        }
        return implode(DIRECTORY_SEPARATOR, $paths);
    }

    /**
     * Similar to the join() method, but also normalize()'s the result
     *
     * @param string|array
     *
     * @return string
     * @see https://github.com/laradic/support/blob/master/src/Path.php
     */
    public static function njoin() : string
    {
        return self::canonicalize(self::join(func_get_args()));
    }

    /**
     * Canonicalizes the given path.
     *
     * During normalization, all slashes are replaced by forward slashes ("/").
     * Furthermore, all "." and ".." segments are removed as far as possible.
     * ".." segments at the beginning of relative paths are not removed.
     *
     * ```php
     * echo DirHelper::canonicalize("\webmozart\puli\..\css\style.css");
     * // => /webmozart/style.css
     *
     * echo DirHelper::canonicalize("../css/./style.css");
     * // => ../css/style.css
     * ```
     *
     * This method is able to deal with both UNIX and Windows paths.
     *
     * @param string $path A path string
     *
     * @return string The canonical path
     * @see https://github.com/laradic/support/blob/master/src/Path.php
     */
    public static function canonicalize($path)
    {
        $path = (string)$path;
        if ('' === $path) {
            return '';
        }
        $path = str_replace('\\', '/', $path);
        list ($root, $path) = self::split($path);
        $parts = array_filter(explode('/', $path), 'strlen');
        $canonicalParts = [];
        // Collapse dot folder ., .., i f possible
        foreach ($parts as $part) {
            self::collapseDotFolder($root, $part, $canonicalParts);
        }
        // Add the root directory again
        return $root . implode('/', $canonicalParts);
    }

    /**
     * Collapse dot folder '.', '..', if possible
     * @param string $root
     * @param $part
     * @param $canonicalParts
     */
    protected static function collapseDotFolder($root, $part, &$canonicalParts)
    {
        if ('.' === $part) {
            return;
        }
        // Collapse ".." with the previous part, if one exists
        // Don't collapse ".." if the previous part is also ".."
        if ('..' === $part && count($canonicalParts) > 0
            && '..' !== $canonicalParts[count($canonicalParts) - 1]
        ) {
            array_pop($canonicalParts);
            return;
        }
        // Only add ".." prefixes for relative paths
        if ('..' !== $part || '' === $root) {
            $canonicalParts[] = $part;
        }
    }

    /**
     * Splits a part into its root directory and the remainder.
     *
     * If the path has no root directory, an empty root directory will be
     * returned.
     *
     * If the root directory is a Windows style partition, the resulting root
     * will always contain a trailing slash.
     *
     * list ($root, $path) = DirHelpersplit("C:/webmozart")
     * // => array("C:/", "webmozart")
     *
     * list ($root, $path) = DirHelpersplit("C:")
     * // => array("C:/", "")
     *
     * @param string $path The canonical path to split
     *
     * @return string[] An array with the root directory and the remaining relative
     *               path
     * @see https://github.com/laradic/support/blob/master/src/Path.php
     */
    private static function split($path)
    {
        if ('' === $path) {
            return ['', ''];
        }
        $root = '';
        $length = strlen($path);
        // Remove and remember root directory
        if ('/' === $path[0]) {
            $root = '/';
            $path = $length > 1 ? substr($path, 1) : '';
        } elseif ($length > 1 && ctype_alpha($path[0]) && ':' === $path[1]) {
            if (2 === $length) {
                // Windows special case: "C:"
                $root = $path . '/';
                $path = '';
            } elseif ('/' === $path[2]) {
                // Windows normal case: "C:/"..
                $root = substr($path, 0, 3);
                $path = $length > 3 ? substr($path, 3) : '';
            }
        }
        return [$root, $path];
    }

    /**
     * Check if a directory is empty in efficent way.
     * Check hidden files too.
     * @param string $path
     * @return bool
     */
    public static function isDirEmpty(string $path) : bool
    {
        //che if no such dir, not a dir, not readable
        if (!self::isReadable($path)) {
            return false;
        }

        $result = true;
        $handle = opendir($path);
        while (false !== ($entry = readdir($handle))) {
            if (!self::isDotDir($entry)) {
                $result = false;
                break;
            }
        }
        closedir($handle);
        return $result;
    }

    /**
     * Check if an antry is linux dot dir (i.e.: . or .. )
     * @param $entry
     * @return bool
     */
    public static function isDotDir($entry):bool
    {
        return $entry == "." || $entry == "..";
    }

    /**
     * Check if $path is a dir and is readable.
     * Return false is you pass a file.
     * @param string $path
     * @return bool
     */
    public static function isReadable(string $path):bool
    {
        return self::isDirSafe($path) && is_readable($path);
    }
}
