<?php

namespace Padosoft\Io\Test;

use Padosoft\Io\DirHelper;
use Padosoft\Io\FileHelper;
use phpDocumentor\Reflection\File;

class FileHelperTest extends \PHPUnit_Framework_TestCase
{
    use \Padosoft\Io\Test\PathDataProvider;

    protected function setUp()
    {
        //init files and paths needed for tests.
        $this->initFileAndPath();
    }

    protected function tearDown()
    {
        //remove created path during test
        $this->removeCreatedPathDuringTest();
    }

    /**
     * @param $expected
     * @return bool
     */
    protected function expectedIsAnException($expected)
    {
        if (is_array($expected)) {
            return false;
        }

        return strpos($expected, 'Exception') !== false
        || strpos($expected, 'PHPUnit_Framework_') !== false
        || strpos($expected, 'TypeError') !== false;
    }

    /**
     * @test
     * @param $path
     * @param $expected
     * @dataProvider pathForGetFilenameWithoutExtension
     */
    public function getFilenameWithoutExtension($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            FileHelper::getFilenameWithoutExtension($path);
        } else {
            $this->assertEquals($expected, FileHelper::getFilenameWithoutExtension($path));
        }
    }

    /**
     * @test
     * @param $path
     * @param $expected
     * @dataProvider pathForDeleteProvider
     */
    public function unlinkSafe($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            FileHelper::unlinkSafe($path);
        } else {
            $this->assertEquals($expected, FileHelper::unlinkSafe($path));
        }
    }

    /**
     * @test
     * @param $path
     * @param $expected
     * @dataProvider filesProvider
     */
    public function fileExistsSafe($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            FileHelper::fileExistsSafe($path);
        } else {
            $this->assertEquals($expected, FileHelper::fileExistsSafe($path));
        }
    }

    /**
     * @test
     * @param $path
     * @param bool $forceCreateDirIfNotExists if true and path not exists, try to create it.
     * @param $modeMask
     * @param $expected
     * @dataProvider fileToCreateProvider
     */
    public function filePutContentsSafe($path, $forceCreateDirIfNotExists, $modeMask, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            FileHelper::filePutContentsSafe($path, 'dummy', $forceCreateDirIfNotExists, $modeMask);
        } else {
            $this->assertEquals($expected, FileHelper::filePutContentsSafe($path, 'dummy', $forceCreateDirIfNotExists, $modeMask));
            if($expected){
                //if function returns treu, check file exists and its content.
                $this->assertFileExists($path);
                $this->assertEquals('dummy', file_get_contents($path));
            }
        }
    }

    /**
     * @test
     * @param $path
     * @param $expected
     * @dataProvider filesToFindFilesProvider
     */
    public function findFiles($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            FileHelper::findFiles($path);
        } else {
            $result = FileHelper::findFiles($path);
            $this->assertTrue(is_array($result));
            $this->assertInternalType('array', $result);
            $this->assertCount(count($expected), $result);
        }
    }

    /**
     * @test
     * @param $path
     * @param $options
     * @param $expected
     * @dataProvider getPathinfoPartProvider
     */
    public function getPathinfoPartTest($path, $options, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            FileHelper::getPathinfoPart($path, $options);
        } else {
            $this->assertEquals($expected, FileHelper::getPathinfoPart($path, $options));
        }
    }

    /**
     * @test
     * @param $path
     * @param $options
     * @param $expected
     * @dataProvider getDirnameProvider
     */
    public function getDirnameTest($path, $options, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            FileHelper::getDirname($path);
        } else {
            $this->assertEquals($expected, FileHelper::getDirname($path));
        }
    }

    /**
     * @test
     * @param $path
     * @param $options
     * @param $expected
     * @dataProvider getFilenameProvider
     */
    public function getFilenameTest($path, $options, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            FileHelper::getFilename($path);
        } else {
            $this->assertEquals($expected, FileHelper::getFilename($path));
        }
    }

    /**
     * @test
     * @param $path
     * @param $options
     * @param $expected
     * @dataProvider getFilenameWithoutExtensionProvider
     */
    public function getFilenameWithoutExtensionTest($path, $options, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            FileHelper::getFilenameWithoutExtension($path);
        } else {
            $this->assertEquals($expected, FileHelper::getFilenameWithoutExtension($path));
        }
    }

    /**
     * @test
     * @param $path
     * @param $options
     * @param $expected
     * @dataProvider getFilenameExtensionProvider
     */
    public function getFilenameExtensionTest($path, $options, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            FileHelper::getFilenameExtension($path);
        } else {
            $this->assertEquals($expected, FileHelper::getFilenameExtension($path));
        }
    }

    /**
     * @test
     * @param $hasExtension
     * @param $path
     * @param $extension
     * @param $ignoreCase
     * @dataProvider provideHasExtensionTests
     */
    public function hasExtension($hasExtension, $path, $extension, $ignoreCase)
    {
        $this->assertSame($hasExtension, FileHelper::hasExtension($path, $extension, $ignoreCase));
    }

    /**
     * @test
     * @param $path
     * @param $extension
     * @param $pathExpected
     * @dataProvider provideChangeExtensionTests
     */
    public function changeExtension($path, $extension, $pathExpected)
    {
        static $call = 0;
        $this->assertSame($pathExpected, FileHelper::changeExtension($path, $extension));
        $call++;
    }

    /**
     * @test
     * @param $path
     * @param $expected
     * @dataProvider provideisReadableFileTests
     */
    public function isReadable($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            FileHelper::isReadable($path);
        } else {
            $this->assertSame($expected, FileHelper::isReadable($path));
        }
    }
}
