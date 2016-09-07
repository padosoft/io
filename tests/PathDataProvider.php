<?php

namespace Padosoft\Io\Test;


trait PathDataProvider
{
    /**
     * @return array
     */
    public function pathProvider()
    {
        return [
            '' => ['', false],
            'null' => [null, 'TypeError'],
            __DIR__ . '/pippo.txt' => [__DIR__ . '/pippo.txt', false],
            __DIR__ . '/../vendor/autoload.php' => [__DIR__ . '/../vendor/autoload.php', false],
            __DIR__ => [__DIR__, true],
            __DIR__ . '/../vendor/bin/' => [__DIR__ . '/../vendor/bin/', true],
        ];
    }

    /**
     * @return array
     */
    public function filesProvider()
    {
        return [
            '' => ['', false],
            'null' => [null, 'TypeError'],
            __DIR__ . '/pippo.txt' => [__DIR__ . '/pippo.txt', false],
            __DIR__ . '/../vendor/autoload.php' => [__DIR__ . '/../vendor/autoload.php', true],
            __DIR__ => [__DIR__, false],
            __DIR__ . '/../vendor/bin/' => [__DIR__ . '/../vendor/bin/', false],
        ];
    }

    /**
     * @return array
     */
    public function filesToFindFilesProvider()
    {
        return [
            '\'\'' => ['', ['DirHelper.php', 'FileHelper.php', 'LogHelper.php']],
            'null' => [null, 'TypeError'],
            __DIR__ . '/pippo.txt' => [__DIR__ . '/pippo.txt', []],
            __DIR__ . '/resources/dummy.txt' => [__DIR__ . '/resources/dummy.txt', ['resources/dummy.txt','resources/subdir/dummy.txt']],
            __DIR__ . '/resources/dummy.*' => [__DIR__ . '/resources/dummy.*', ['resources/dummy.csv', 'resources/dummy.txt','resources/subdir/dummy.txt']],
            __DIR__ . '/resources' => [__DIR__ . '/resources', []],
            __DIR__ . '/resources/' => [__DIR__ . '/resources/', ['resources/dummy.csv', 'resources/dummy.txt','resources/subdir/dummy.txt']],
            __DIR__ . '/resources/*' => [__DIR__ . '/resources/*', ['resources/dummy.csv', 'resources/dummy.txt','resources/subdir/dummy.txt']],
            __DIR__ . '/../tests/*' => [__DIR__.'/../tests/*', ['1', '2', '3', '4', '5', '6', '7']],
        ];
    }

    /**
     * @return array
     */
    public function filesToFindDirsProvider()
    {
        return [
            '\'\'' => ['', []],
            'null' => [null, 'TypeError'],
            __DIR__ . '/pippo.txt' => [__DIR__ . '/pippo.txt', []],
            __DIR__ . '/resources/dummy.txt' => [__DIR__ . '/resources/dummy.txt', []],
            __DIR__ . '/resources/dummy.*' => [__DIR__ . '/resources/dummy.*', []],
            __DIR__ . '/resources/*' => [__DIR__ . '/resources/*', [__DIR__ . '/resources/subdir']],
            __DIR__ . '/resources' => [__DIR__ . '/resources', [__DIR__ . '/resources/']],
            __DIR__ . '/resources/*' => [__DIR__ . '/resources/*', [__DIR__ . '/resources/subdir']],
            __DIR__ . '/resources/' => [__DIR__ . '/resources/', [__DIR__ . '/resources/']],
        ];
    }


    /**
     * @return array
     */
    public function fileToCreateProvider()
    {
        return [
            '\'\', false, 0755' => ['', false, '0755', false],
            '\'\', true, 0755' => ['', true, '0755', false],
            'null, false, 0755' => [null, false, '0755', 'TypeError'],
            'null, true, 0755' => [null, true, '0755', 'TypeError'],
            __DIR__ . '/resources/dummy.txt, true, 0755' => [__DIR__ . '/resources/dummy.txt', true, '0755', true],
            __DIR__ . '/resources/dummy.txt, false, 0755' => [__DIR__ . '/resources/dummy.txt', false, '0755', true],
            __DIR__ . '/resources/new/dummy.txt, false, 0755' => [__DIR__ . '/resources/new/dummy.txt', false, '0755', false],
            __DIR__ . '/resources/new/dummy.txt, true, 0755' => [__DIR__ . '/resources/new/dummy.txt', true, '0755', true],
            __DIR__ . '/resources/, false, 0755' => [__DIR__ . '/resources/', false, '0755', false],
            __DIR__ . '/resources/, true, 0755' => [__DIR__ . '/resources/', true, '0755', false],
            __DIR__ . '/resources/new2/, false, 0755' => [__DIR__ . '/resources/new2/', false, '0755', false],
            __DIR__ . '/resources/new2/, true, 0755' => [__DIR__ . '/resources/new2/', true, '0755', false],
        ];
    }

    /**
     * @return array
     */
    public function pathForDeleteProvider()
    {
        return [
            '' => ['', false],
            'null' => [null, 'TypeError'],
            __DIR__ . '/pippo.txt' => [__DIR__ . '/pippo.txt', false],
            __DIR__ . '/resources/dummy.txt' => [__DIR__ . '/resources/dummy.txt', true],
            __DIR__ . '/resources/' => [__DIR__ . '/resources/', false],
        ];
    }

    /**
     * @return array
     */
    public function pathForAddFinalSlashProvider()
    {
        return [
            '' => ['', '/'],
            'null' => [null, 'TypeError'],
            __DIR__ . '/pippo.txt' => [__DIR__ . '/pippo.txt', __DIR__ . '/pippo.txt/'],
            __DIR__ . '/../vendor/' => [__DIR__ . '/../vendor/', __DIR__ . '/../vendor/'],
            __DIR__ . '/../vendor' => [__DIR__ . '/../vendor', __DIR__ . '/../vendor/'],
            __DIR__ . '/../' => [__DIR__ . '/../', __DIR__ . '/../'],
            __DIR__ . '/..' => [__DIR__ . '/..', __DIR__ . '/../'],
            __DIR__ . '/resources' => [__DIR__ . '/resources', __DIR__ . '/resources/'],
            __DIR__ => [__DIR__, __DIR__ . '/'],
            __DIR__ . '/../vendor/2/3/4/5/' => [__DIR__ . '/../vendor/2/3/4/5/', __DIR__ . '/../vendor/2/3/4/5/'],
            __DIR__ . '/../vendor/2/3/4/5' => [__DIR__ . '/../vendor/2/3/4/5', __DIR__ . '/../vendor/2/3/4/5/'],
        ];
    }

    /**
     * @return array
     */
    public function pathForEndsWithSlashProvider()
    {
        return [
            '' => ['', false],
            'null' => [null, 'TypeError'],
            __DIR__ . '/pippo.txt' => [__DIR__ . '/pippo.txt', false],
            __DIR__ . '/../vendor/' => [__DIR__ . '/../vendor/', true],
            __DIR__ . '/../vendor' => [__DIR__ . '/../vendor', false],
            __DIR__ . '/../' => [__DIR__ . '/../', true],
            __DIR__ . '/..' => [__DIR__ . '/..', false],
            __DIR__ => [__DIR__, false],
            __DIR__ . '/../vendor/2/3/4/5/' => [__DIR__ . '/../vendor/2/3/4/5/', true],
            __DIR__ . '/../vendor/2/3/4/5' => [__DIR__ . '/../vendor/2/3/4/5', false],
        ];
    }

    /**
     * @return array
     */
    public function pathForEndsWithStarProvider()
    {
        return [
            '' => ['', false],
            'null' => [null, 'TypeError'],
            __DIR__ . '/pippo.txt' => [__DIR__ . '/pippo.txt', false],
            __DIR__ . '/pippo.txt*' => [__DIR__ . '/pippo.txt*', true],
            __DIR__ . '/../vendor/*' => [__DIR__ . '/../vendor/*', true],
            __DIR__ . '/../vendor' => [__DIR__ . '/../vendor', false],
            __DIR__ . '/../*' => [__DIR__ . '/../*', true],
            __DIR__ . '/..' => [__DIR__ . '/..', false],
            __DIR__ . '/../vendor/2/3/4/5/*' => [__DIR__ . '/../vendor/2/3/4/5/*', true],
            __DIR__ . '/../vendor/2/3/4/5' => [__DIR__ . '/../vendor/2/3/4/5', false],
        ];
    }

    /**
     * @return array
     */
    public function pathForStartsWithSlashProvider()
    {
        return [
            '' => ['', false],
            'null' => [null, 'TypeError'],
            'dfsfs.txt' => ['dfsfs.txt', false],
            '../vendor/' => ['../vendor/', false],
            '/../vendor' => ['/../vendor', true],
            '/../' => ['/../', true],
            '../' => ['../', false],
            '/..' => ['/..', true],
            '/' => ['/', true],
        ];
    }

    /**
     * @return array
     */
    public function pathForAddFinalSlashToAllPathProvider()
    {
        return [
            [[], []],
            [null, 'TypeError'],
            [
                [
                    '',
                    __DIR__ . '/pippo.txt',
                    __DIR__ . '/../vendor/',
                    __DIR__ . '/../vendor',
                    __DIR__ . '/../',
                    __DIR__ . '/..',
                    __DIR__,
                    __DIR__ . '/../vendor/2/3/4/5/',
                    __DIR__ . '/../vendor/2/3/4/5',
                ],
                [
                    '/',
                    __DIR__ . '/pippo.txt/',
                    __DIR__ . '/../vendor/',
                    __DIR__ . '/../vendor/',
                    __DIR__ . '/../',
                    __DIR__ . '/../',
                    __DIR__ . '/',
                    __DIR__ . '/../vendor/2/3/4/5/',
                    __DIR__ . '/../vendor/2/3/4/5/',
                ]
            ],
        ];
    }

    /**
     * @return array
     */
    public function pathAndMaskProvider()
    {
        return [
            '\'\', 0755' => ['', '0755', 'PHPUnit_Framework_Error'],
            'null, 0755' => [null, '0755', 'TypeError'],
            __DIR__ . '/pippo.txt, 0755' => [__DIR__ . '/pippo.txt', '0755', true],
            __DIR__ . '/../vendor/autoload.php, 0755' => [__DIR__ . '/../vendor/autoload.php', '0755', false],
            __DIR__ . '/../vendor/autoload.php, 0755' => [__DIR__ . '/../vendor/autoload.php', '0755', false],
            __DIR__ . ', 0755' => [__DIR__, '0755', true],
            __DIR__ . '/../vendor/bin/ , 0755' => [__DIR__ . '/../vendor/bin/', '0755', true],
            __DIR__ . '/dummy/ , 0755' => [__DIR__ . '/dummy/', '0755', true],
        ];
    }

    /**
     * @return array
     */
    public function getPathinfoPartProvider()
    {
        return [
            '\'\', PATHINFO_DIRNAME' => ['', PATHINFO_DIRNAME, ''],
            '\'\', PATHINFO_BASENAME' => ['', PATHINFO_BASENAME, ''],
            '\'\', PATHINFO_EXTENSION' => ['', PATHINFO_EXTENSION, ''],
            '\'\', PATHINFO_FILENAME' => ['', PATHINFO_FILENAME, ''],
            'null, PATHINFO_DIRNAME' => [null, PATHINFO_DIRNAME, 'TypeError'],
            'null, PATHINFO_BASENAME' => [null, PATHINFO_BASENAME, 'TypeError'],
            'null, PATHINFO_EXTENSION' => [null, PATHINFO_EXTENSION, 'TypeError'],
            'null, PATHINFO_FILENAME' => [null, PATHINFO_FILENAME, 'TypeError'],
            __DIR__ . '/pippo.txt, PATHINFO_DIRNAME' => [__DIR__ . '/pippo.txt', PATHINFO_DIRNAME, __DIR__],
            __DIR__ . '/pippo.txt, PATHINFO_BASENAME' => [__DIR__ . '/pippo.txt', PATHINFO_BASENAME, 'pippo.txt'],
            __DIR__ . '/pippo.txt, PATHINFO_EXTENSION' => [__DIR__ . '/pippo.txt', PATHINFO_EXTENSION, 'txt'],
            __DIR__ . '/pippo.txt, PATHINFO_FILENAME' => [__DIR__ . '/pippo.txt', PATHINFO_FILENAME, 'pippo'],
            __DIR__ . '/../vendor/autoload.php, PATHINFO_DIRNAME' => [__DIR__ . '/../vendor/autoload.php', PATHINFO_DIRNAME, __DIR__.'/../vendor'],
            __DIR__ . '/../vendor/autoload.php, PATHINFO_BASENAME' => [__DIR__ . '/../vendor/autoload.php', PATHINFO_BASENAME, 'autoload.php'],
            __DIR__ . '/../vendor/autoload.php, PATHINFO_EXTENSION' => [__DIR__ . '/../vendor/autoload.php', PATHINFO_EXTENSION, 'php'],
            __DIR__ . '/../vendor/autoload.php, PATHINFO_FILENAME' => [__DIR__ . '/../vendor/autoload.php', PATHINFO_FILENAME, 'autoload'],
            '/var/www/vhosts/, PATHINFO_DIRNAME' => ['/var/www/vhosts/', PATHINFO_DIRNAME, ''],
            '/var/www/vhosts/, PATHINFO_BASENAME' => ['/var/www/vhosts/', PATHINFO_BASENAME, ''],
            '/var/www/vhosts/, PATHINFO_EXTENSION' => ['/var/www/vhosts/', PATHINFO_EXTENSION, ''],
            '/var/www/vhosts/, PATHINFO_FILENAME' => ['/var/www/vhosts/', PATHINFO_FILENAME, ''],
            '/var/www/vhosts, PATHINFO_DIRNAME' => ['/var/www/vhosts', PATHINFO_DIRNAME, '/var/www'],
            '/var/www/vhosts, PATHINFO_BASENAME' => ['/var/www/vhosts', PATHINFO_BASENAME, 'vhosts'],
            '/var/www/vhosts, PATHINFO_EXTENSION' => ['/var/www/vhosts', PATHINFO_EXTENSION, ''],
            '/var/www/vhosts, PATHINFO_FILENAME' => ['/var/www/vhosts', PATHINFO_FILENAME, 'vhosts'],
            'vhosts, PATHINFO_DIRNAME' => ['vhosts', PATHINFO_DIRNAME, ''],
            'vhosts, PATHINFO_BASENAME' => ['vhosts', PATHINFO_BASENAME, 'vhosts'],
            'vhosts, PATHINFO_EXTENSION' => ['vhosts', PATHINFO_EXTENSION, ''],
            'vhosts, PATHINFO_FILENAME' => ['vhosts', PATHINFO_FILENAME, 'vhosts'],
            '.htaccess, PATHINFO_DIRNAME' => ['.htaccess', PATHINFO_DIRNAME, ''],
            '.htaccess, PATHINFO_BASENAME' => ['.htaccess', PATHINFO_BASENAME, '.htaccess'],
            '.htaccess, PATHINFO_EXTENSION' => ['.htaccess', PATHINFO_EXTENSION, 'htaccess'],
            '.htaccess, PATHINFO_FILENAME' => ['.htaccess', PATHINFO_FILENAME, ''],
            '., PATHINFO_DIRNAME' => ['.', PATHINFO_DIRNAME, ''],
            '., PATHINFO_BASENAME' => ['.', PATHINFO_BASENAME, ''],
            '., PATHINFO_EXTENSION' => ['.', PATHINFO_EXTENSION, ''],
            '., PATHINFO_FILENAME' => ['.', PATHINFO_FILENAME, ''],
        ];
    }

    /**
     * @return array
     */
    public function getFilenameExtensionProvider()
    {
        return [
            '\'\', PATHINFO_EXTENSION' => ['', PATHINFO_EXTENSION, ''],
            'null, PATHINFO_EXTENSION' => [null, PATHINFO_EXTENSION, 'TypeError'],
            __DIR__ . '/pippo.txt, PATHINFO_EXTENSION' => [__DIR__ . '/pippo.txt', PATHINFO_EXTENSION, 'txt'],
            __DIR__ . '/../vendor/autoload.php, PATHINFO_EXTENSION' => [__DIR__ . '/../vendor/autoload.php', PATHINFO_EXTENSION, 'php'],
            '/var/www/vhosts/, PATHINFO_EXTENSION' => ['/var/www/vhosts/', PATHINFO_EXTENSION, ''],
            '/var/www/vhosts, PATHINFO_EXTENSION' => ['/var/www/vhosts', PATHINFO_EXTENSION, ''],
            'vhosts, PATHINFO_EXTENSION' => ['vhosts', PATHINFO_EXTENSION, ''],
            '.htaccess, PATHINFO_EXTENSION' => ['.htaccess', PATHINFO_EXTENSION, 'htaccess'],
            '., PATHINFO_EXTENSION' => ['.', PATHINFO_EXTENSION, ''],
        ];
    }

    /**
     * @return array
     */
    public function getFilenameWithoutExtensionProvider()
    {
        return [
            '\'\', PATHINFO_FILENAME' => ['', PATHINFO_FILENAME, ''],
            'null, PATHINFO_FILENAME' => [null, PATHINFO_FILENAME, 'TypeError'],
            __DIR__ . '/pippo.txt, PATHINFO_FILENAME' => [__DIR__ . '/pippo.txt', PATHINFO_FILENAME, 'pippo'],
            __DIR__ . '/../vendor/autoload.php, PATHINFO_FILENAME' => [__DIR__ . '/../vendor/autoload.php', PATHINFO_FILENAME, 'autoload'],
            '/var/www/vhosts/, PATHINFO_FILENAME' => ['/var/www/vhosts/', PATHINFO_FILENAME, ''],
            '/var/www/vhosts, PATHINFO_FILENAME' => ['/var/www/vhosts', PATHINFO_FILENAME, 'vhosts'],
            'vhosts, PATHINFO_FILENAME' => ['vhosts', PATHINFO_FILENAME, 'vhosts'],
            '.htaccess, PATHINFO_FILENAME' => ['.htaccess', PATHINFO_FILENAME, ''],
            '., PATHINFO_FILENAME' => ['.', PATHINFO_FILENAME, ''],
        ];
    }

    /**
     * @return array
     */
    public function getFilenameProvider()
    {
        return [
            '\'\', PATHINFO_BASENAME' => ['', PATHINFO_BASENAME, ''],
            'null, PATHINFO_BASENAME' => [null, PATHINFO_BASENAME, 'TypeError'],
            __DIR__ . '/pippo.txt, PATHINFO_BASENAME' => [__DIR__ . '/pippo.txt', PATHINFO_BASENAME, 'pippo.txt'],
            __DIR__ . '/../vendor/autoload.php, PATHINFO_BASENAME' => [__DIR__ . '/../vendor/autoload.php', PATHINFO_BASENAME, 'autoload.php'],
            '/var/www/vhosts/, PATHINFO_BASENAME' => ['/var/www/vhosts/', PATHINFO_BASENAME, ''],
            '/var/www/vhosts, PATHINFO_BASENAME' => ['/var/www/vhosts', PATHINFO_BASENAME, 'vhosts'],
            'vhosts, PATHINFO_BASENAME' => ['vhosts', PATHINFO_BASENAME, 'vhosts'],
            '.htaccess, PATHINFO_BASENAME' => ['.htaccess', PATHINFO_BASENAME, '.htaccess'],
            '., PATHINFO_BASENAME' => ['.', PATHINFO_BASENAME, ''],
        ];
    }

    /**
     * @return array
     */
    public function getDirnameProvider()
    {
        return [
            '\'\', PATHINFO_DIRNAME' => ['', PATHINFO_DIRNAME, ''],
            'null, PATHINFO_DIRNAME' => [null, PATHINFO_DIRNAME, 'TypeError'],
            __DIR__ . '/pippo.txt, PATHINFO_DIRNAME' => [__DIR__ . '/pippo.txt', PATHINFO_DIRNAME, __DIR__],
            __DIR__ . '/../vendor/autoload.php, PATHINFO_DIRNAME' => [__DIR__ . '/../vendor/autoload.php', PATHINFO_DIRNAME, __DIR__.'/../vendor'],
            '/var/www/vhosts/, PATHINFO_DIRNAME' => ['/var/www/vhosts/', PATHINFO_DIRNAME, ''],
            '/var/www/vhosts, PATHINFO_DIRNAME' => ['/var/www/vhosts', PATHINFO_DIRNAME, '/var/www'],
            'vhosts, PATHINFO_DIRNAME' => ['vhosts', PATHINFO_DIRNAME, ''],
            '.htaccess, PATHINFO_DIRNAME' => ['.htaccess', PATHINFO_DIRNAME, ''],
            '., PATHINFO_DIRNAME' => ['.', PATHINFO_DIRNAME, ''],
        ];
    }

    /**
     * @return array
     */
    public function pathForGetFilenameWithoutExtension()
    {
        return [
            '' => ['', ''],
            'null' => [null, 'TypeError'],
            __DIR__ . '/pippo.txt' => [__DIR__ . '/pippo.txt', 'pippo'],
            '/pippo.txt' => ['/pippo.txt', 'pippo'],
            __DIR__ . '/../vendor/' => [__DIR__ . '/../vendor/', ''],
            __DIR__ . '/../vendor' => [__DIR__ . '/../vendor', ''],
            __DIR__ . '/../' => [__DIR__ . '/../', ''],
            __DIR__ . '/..' => [__DIR__ . '/..', ''],
            __DIR__ => [__DIR__, ''],
            '../vendor/2/3/4/5/' => ['../vendor/2/3/4/5/', ''],
            '../vendor/2/3/4/5' => ['../vendor/2/3/4/5', '5'],
            '../vendor/2/3/4/5/pippo.txt' => ['../vendor/2/3/4/5/pippo.txt', 'pippo'],
            '../vendor/2/3/4/5/pippo.pluto.txt' => ['../vendor/2/3/4/5/pippo.pluto.txt', 'pippo.pluto'],
            '../vendor/2/3/4/5/.env' => ['../vendor/2/3/4/5/.env', ''],
        ];
    }

    /**
     * Remove created path during test
     */
    protected function initFileAndPath()
    {
        $dir = __DIR__ . '/resources/';
        if (!is_dir($dir)) {
            mkdir($dir, '0777', true);
        }

        $file = $dir . 'dummy.txt';
        if (!file_exists($file)) {
            file_put_contents($file, 'dummy');
        }

        $file = $dir . 'dummy.csv';
        if (!file_exists($file)) {
            file_put_contents($file, 'dummy;');
        }

        $dir = __DIR__ . '/resources/subdir/';
        if (!is_dir($dir)) {
            mkdir($dir, '0777', true);
        }

        $file = $dir . 'dummy.txt';
        if (!file_exists($file)) {
            file_put_contents($file, 'dummy');
        }
    }

    /**
     * Remove created path during test
     */
    protected function removeCreatedPathDuringTest()
    {
        if (is_dir(__DIR__ . '/pippo.txt')) {
            rmdir(__DIR__ . '/pippo.txt');
        }
        if (is_dir(__DIR__ . '/dummy/')) {
            rmdir(__DIR__ . '/dummy/');
        }
        if (file_exists(__DIR__ . '/resources/new/dummy.txt')) {
            unlink(__DIR__ . '/resources/new/dummy.txt');
        }
        if (is_dir(__DIR__ . '/resources/new/')) {
            rmdir(__DIR__ . '/resources/new/');
        }
        if (file_exists(__DIR__ . '/resources/new2/dummy.txt')) {
            unlink(__DIR__ . '/resources/new2/dummy.txt');
        }
        if (is_dir(__DIR__ . '/resources/new2/')) {
            rmdir(__DIR__ . '/resources/new2/');
        }

        /*
        $dir = __DIR__ . '/resources/subdir/';

        $file = $dir . 'dummy.txt';
        if (file_exists($file)) {
            unlink($file);
        }
        if (is_dir($dir)) {
            rmdir($dir);
        }

        $dir = __DIR__ . '/resources/';

        $file = $dir . 'dummy.txt';
        if (file_exists($file)) {
            unlink($file);
        }
        $file = $dir . 'dummy.csv';
        if (file_exists($file)) {
            unlink($file);
        }
        if (is_dir($dir)) {
            rmdir($dir);
        }
        */
    }

}
