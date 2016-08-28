<?php

namespace Padosoft\Io;

/**
 * Helper Class FileHelper
 * @package Padosoft\Io
 */
class FileHelper
{
    public static $arrMimeType = array(

        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',

        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );

    /**
     * Simple pathinfo wrapper.
     * @param string $filePath
     * @param int $fileInfOoptions
     * @return string
     * @see http://php.net/manual/en/function.pathinfo.php
     */
    public static function getPathinfoPart(string $filePath, int $fileInfOoptions) : string
    {
        if ($filePath === null || $filePath == '' || is_dir($filePath) || DirHelper::endsWithSlash($filePath)) {
            return '';
        }

        $info = pathinfo($filePath, $fileInfOoptions);

        if ($info == '.' && $fileInfOoptions == PATHINFO_DIRNAME) {
            return '';
        }
        return ($info !== null && $info != '') ? $info : '';
    }

    /**
     * Return the file name of file (without path and without extension).
     * Return empty string if $filePath is null, empty or is a directory.
     * Ex.: /public/upload/pippo.txt return '/public/upload'
     * @param string $filePath
     * @return string
     */
    public static function getDirname(string $filePath) : string
    {
        return self::getPathinfoPart($filePath, PATHINFO_DIRNAME);
    }

    /**
     * Return the file name of file (without path and with extension).
     * Return empty string if $filePath is null, empty or is a directory.
     * Ex.: /public/upload/pippo.txt return 'pippo.txt'
     * @param string $filePath
     * @return string
     */
    public static function getFilename(string $filePath) : string
    {
        return self::getPathinfoPart($filePath, PATHINFO_BASENAME);
    }

    /**
     * Return the file name of file (without path and without extension).
     * Return empty string if $filePath is null, empty or is a directory.
     * Ex.: /public/upload/pippo.txt return 'pippo'
     * @param string $filePath
     * @return string
     */
    public static function getFilenameWithoutExtension(string $filePath) : string
    {
        return self::getPathinfoPart($filePath, PATHINFO_FILENAME);
    }

    /**
     * Return the file name of file (without path and without extension).
     * Return empty string if $filePath is null, empty or is a directory.
     * Ex.: /public/upload/pippo.txt return '.txt'
     * @param string $filePath
     * @return string
     */
    public static function getFilenameExtension(string $filePath) : string
    {
        return self::getPathinfoPart($filePath, PATHINFO_EXTENSION);
    }

    /**
     * unlink file if exists.
     * Return false if exists and unlink fails or if filePath is a dir.
     * @param string $filePath
     * @return bool
     */
    public static function unlinkSafe(string $filePath) : bool
    {
        if (!FileHelper::fileExistsSafe($filePath)) {
            return false;
        }

        if (DirHelper::isDirSafe($filePath)) {
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
        if ($filePath === null || $filePath == '') {
            return false;
        }

        if (DirHelper::isDirSafe($filePath)) {
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
            $fileNamePattern = DirHelper::addFinalSlash(base_path()) . '*';
        } elseif ($fileNamePattern === null || $fileNamePattern == '') {
            $fileNamePattern = __DIR__ . '/*';
        }

        if (DirHelper::endsWithSlash($fileNamePattern)) {
            $fileNamePattern .= '*';
        }

        $files = glob($fileNamePattern, $flags);

        //remove array of empty string
        $files = array_filter($files, function ($k) {
            return ($k !== null && $k != '');
        });

        if (empty($files)) {
            return $fallback;
        }

        foreach (glob(dirname($fileNamePattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {

            if (empty($dir)) {
                continue;
            }

            $files = array_merge($files, self::findFiles($dir . '/' . basename($fileNamePattern), $flags));
            $files = array_filter($files, function ($k) {
                return ($k !== null && $k != '');
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
        if (DirHelper::endsWithSlash($filename)) {
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

    /**
     * Return mime type of a passed file in optimized mode.
     * @param string $fullPathFile
     * @return string
     */
    public static function getMimeType(string $fullPathFile) : string
    {
        $mime_types = self::$arrMimeType;
        $ext = strtolower(self::getFilenameExtension($fullPathFile));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        $mimetype = self::getMimeTypeByMimeContentType($fullPathFile);
        if (isNotNullOrEmpty($mimetype)) {
            return $mimetype;
        }
        $mimetype = self::getMimeTypeByFinfo($fullPathFile);
        if (isNotNullOrEmpty($mimetype)) {
            return $mimetype;
        }
        return 'application/octet-stream';
    }

    /**
     * Return mime type of a passed file using finfo
     * @param string $fullPathFile
     * @return string return empty string if it fails.
     */
    public static function getMimeTypeByFinfo(string $fullPathFile) : string
    {
        if (!function_exists('finfo_open')) {
            return '';
        }
        $finfo = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, $fullPathFile);
        finfo_close($finfo);
        if ($mimetype === false) {
            return '';
        }
        return $mimetype;
    }

    /**
     * Return mime type of a passed file using mime_content_type()
     * @param string $fullPathFile
     * @return string return empty string if it fails.
     */
    public static function getMimeTypeByMimeContentType(string $fullPathFile) : string
    {
        if (!function_exists('mime_content_type')) {
            return '';
        }
        return mime_content_type($fullPathFile);
    }
}
