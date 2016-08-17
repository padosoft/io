<?php

namespace Padosoft\Io\Test;

use Padosoft\Io\FileHelper;

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
}
