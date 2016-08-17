<?php

namespace Padosoft\Io;

use Padosoft\Io\DirHelper;

/**
 * Helper Class FileHelper
 * @package Padosoft\Io
 */
class FileHelper
{
    /**
     * Return the file name of file (without path and witout extension).
     * Return empty string if $filePath is null, empty or is a directory.
     * Ex.: \public\upload\pippo.txt return 'pippo'
     * @param string $filePath
     * @return string
     */
    public static function getFilenameWithoutExtension(string $filePath) : string
    {
        if (!$filePath) {
            return '';
        }

        if (is_dir($filePath)) {
            return '';
        }

        //if ends with '/' is a dir
        if (($temp = strlen($filePath) - strlen('/')) >= 0 && strpos($filePath, '/', $temp) !== false) {
            return '';
        }

        $info = pathinfo($filePath);

        return (is_array($info) && array_key_exists('filename', $info)) ? $info['filename'] : '';
    }

    /**
     * unlink file if exists.
     * Return false if exists and unlink fails or if filePath is a dir.
     * @param string $filePath
     * @return bool
     */
    public static function unlinkSafe(string $filePath) : bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        if (is_dir($filePath)) {
            return false;
        }

        return unlink($filePath);
    }

    /**
     * Check if passed file exists or not.
     * If dir passed return false.
     * @param string $filePath
     * @return bool
     */
    public static function fileExistsSafe(string $filePath) : bool
    {
        if (!$filePath) {
            return false;
        }

        if (is_dir($filePath)) {
            return false;
        }

        return file_exists($filePath);
    }

    /**
     * Find files matching a pattern (recursive with matched files in subdirs).
     * Returns an array containing the matched files (full path and not directories),
     * an empty array if no file matched or on error.
     * @param string $fileNamePattern if is null it set to base_path()/* if exists otherwise __DIR__/* . It support glob() string pattern.
     * @param int $flags glob() Valid flags
     * @return array of files (full path)
     */
    public static function findFiles(string $fileNamePattern, int $flags = 0)
    {
        $fallback = [];

        if (($fileNamePattern === null || $fileNamePattern == '') && function_exists('base_path')) {
            $fileNamePattern = DirHelper::addFinalSlash(base_path()).'*';
        }elseif ($fileNamePattern === null || $fileNamePattern == '') {
            $fileNamePattern = __DIR__.'/*';
        }

        if(DirHelper::endsWithSlash($fileNamePattern)){
            $fileNamePattern .= '*';
        }

        $files = glob($fileNamePattern, $flags);

        //remove array of empty string
        $files = array_filter($files, function ($k) {
            return ($k!==null && $k != '');
        });

        if(empty($files)){
            return $fallback;
        }

        foreach (glob(dirname($fileNamePattern).'/*',GLOB_ONLYDIR|GLOB_NOSORT) as $dir){

            if(empty($dir)){
                continue;
            }

            $files = array_merge($files, self::findFiles($dir.'/'.basename($fileNamePattern), $flags));
            $files = array_filter($files, function ($k) {
                return ($k!==null && $k != '');
            });
        }

        $files = array_filter($files, function ($k) {
            return (!is_dir($k));
        });

        return $files === false ? $fallback : $files;
    }

    /**
     * Equals to file_put_contents but safe, i.e.
     * accept empty string and return false without raise an error,
     * accept a directory and return false without raise an error,
     * and if $forceCreateDirIfNotExists is set to true and path doesn't exists, file_put_contents fails
     * so, this class, try to create the complete path before save file.
     * @param string $filename file name including folder.
     * example :: /path/to/file/filename.ext or filename.ext
     * @param string $data The data to write.
     * @param bool $forceCreateDirIfNotExists if true and path not exists, try to create it.
     * @param string $modeMask The mask applied to dir if need to create some dir.
     * @param int $flags same flags used for file_put_contents.
     * @see more info: http://php.net/manual/en/function.file-put-contents.php
     * @return bool TRUE file created succesfully, return FALSE if failed to create file.
     */
    public static function filePutContentsSafe(
        string $filename,
        string $data,
        bool $forceCreateDirIfNotExists = true,
        string $modeMask = '0755',
        int $flags = 0
    ) : bool
    {
        if ($filename === null || $filename == '') {
            return false;
        }

        //check if a directory passed ($filename ends with slash)
        if (($temp = strlen($filename) - strlen('/')) >= 0 && strpos($filename, '/', $temp) !== false) {
            return false;
        }

        $dirName = dirname($filename);

        if (!$forceCreateDirIfNotExists && !DirHelper::isDirSafe($dirName)) {
            return false;
        }

        if (!DirHelper::checkDirExistOrCreate(DirHelper::addFinalSlash($dirName), $modeMask)) {
            return false;
        }

        return file_put_contents($filename, $data, $flags);
    }
}
