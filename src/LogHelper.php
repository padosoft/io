<?php

namespace Padosoft\Io;

/**
 * Class LogHelper
 * @package Padosoft\Io
 */
class LogHelper
{
    /**
     * Truncates a file to a given length but keep the end.
     * Takes the filepointer, handle, and truncates the file to length, size.
     * It will not just cut it but search for a newline so you avoid corrupting your csv or logfiles.
     * @param string $filename
     * @param $maxfilesize in byte
     * @return bool
     * @see http://php.net/manual/en/function.ftruncate.php#103591
     */
    public static function ftruncatestart(string $filename, $maxfilesize) : bool
    {
        if (!FileHelper::fileExistsSafe($filename)) {
            return false;
        }
        $size = filesize($filename);
        if ($size === false || ($size < $maxfilesize * 1.0)) {
            return false;
        }
        $maxfilesize = $maxfilesize * 0.5; //we don't want to do it too often...
        $fh = fopen($filename, "r+");
        if ($fh === false) {
            return false;
        }
        ftell($fh);
        fseek($fh, -$maxfilesize, SEEK_END);
        $drop = fgets($fh);
        $offset = ftell($fh);
        for ($x = 0; $x < $maxfilesize; $x++) {
            fseek($fh, $x + $offset);
            $c = fgetc($fh);
            fseek($fh, $x);
            fwrite($fh, $c);
        }
        ftruncate($fh, $maxfilesize - strlen($drop));
        fclose($fh);

        return true;
    }
}
