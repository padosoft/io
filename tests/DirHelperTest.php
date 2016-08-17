<?php

namespace Padosoft\Io\Test;

use Padosoft\Io\DirHelper;

class DirHelperTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider pathProvider
     */
    public function isDirSafe($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            DirHelper::isDirSafe($path);
        } else {
            $this->assertEquals($expected, DirHelper::isDirSafe($path));
        }
    }

    /**
     * @test
     * @param $path
     * @param $modeMask
     * @param $expected
     * @dataProvider pathAndMaskProvider
     */
    public function checkDirExistOrCreate($path, $modeMask, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            DirHelper::checkDirExistOrCreate($path, $modeMask);
        } else {
            $this->assertEquals($expected, DirHelper::checkDirExistOrCreate($path, $modeMask));
            $this->assertEquals($expected, is_dir($path));
        }
    }

    /**
     * @test
     * @param $path
     * @param $expected
     * @dataProvider pathForAddFinalSlashProvider
     */
    public function addFinalSlash($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            DirHelper::addFinalSlash($path);
        } else {
            $this->assertEquals($expected, DirHelper::addFinalSlash($path));
        }
    }

    /**
     * @test
     * @param $path
     * @param $expected
     * @dataProvider pathForAddFinalSlashToAllPathProvider
     */
    public function addFinalSlashToAllPaths($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            DirHelper::addFinalSlashToAllPaths($path);
        } else {
            $this->assertEquals($expected, DirHelper::addFinalSlashToAllPaths($path));
        }
    }

    /**
     * @test
     * @param $path
     * @param $expected
     * @dataProvider pathForEndsWithSlashProvider
     */
    public function endsWithSlash($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            DirHelper::endsWithSlash($path);
        } else {
            $this->assertEquals($expected, DirHelper::endsWithSlash($path));
        }
    }

    /**
     * @test
     * @param $path
     * @param $expected
     * @dataProvider pathForEndsWithStarProvider
     */
    public function endsWithStar($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            DirHelper::endsWithStar($path);
        } else {
            $this->assertEquals($expected, DirHelper::endsWithStar($path));
        }
    }

    /**
     * @test
     * @param $path
     * @param $expected
     * @dataProvider pathForEndsWithSlashProvider
     */
    public function endsWith($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            DirHelper::endsWith($path, '/');
        } else {
            $this->assertEquals($expected, DirHelper::endsWith($path, '/'));
        }
    }

    /**
     * @test
     * @param $path
     * @param $expected
     * @dataProvider pathForStartsWithSlashProvider
     */
    public function startsWithSlash($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            DirHelper::startsWithSlash($path);
        } else {
            $this->assertEquals($expected, DirHelper::startsWithSlash($path));
        }
    }

    /**
     * @test
     * @param $path
     * @param $expected
     * @dataProvider pathForStartsWithSlashProvider
     */
    public function startsWith($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            DirHelper::startsWith($path, '/');
        } else {
            $this->assertEquals($expected, DirHelper::startsWith($path, '/'));
        }
    }

    /**
     * @test
     * @param $path
     * @param $expected
     * @dataProvider filesToFindDirsProvider
     */
    public function findDirs($path, $expected)
    {
        if ($this->expectedIsAnException($expected)) {
            $this->expectException($expected);
            DirHelper::findDirs($path);
        } else {
            $result = DirHelper::findDirs($path);
            $this->assertTrue(is_array($result));
            $this->assertInternalType('array', $result);
            $this->assertCount(count($expected), $result);
        }
    }
}
