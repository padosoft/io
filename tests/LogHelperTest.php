<?php

namespace Padosoft\Io\Test;

use Padosoft\Io\LogHelper;
use PHPUnit\Framework\TestCase;

class LogHelperTest extends TestCase
{
    use \Padosoft\Io\Test\PathDataProvider;

    protected function setUp(): void
    {
        //init files and paths needed for tests.
        $this->initFileAndPath();

        $dir = __DIR__ . '/resources/';
        if (!is_dir($dir)) {
            mkdir($dir, '0777', true);
        }

        $file = $dir . 'log.txt';
        if (!file_exists($file)) {
            $strLog = '';
            for ($i = 0; $i < 100; $i++) {
                $strLog .= $i . ';uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;
            }
            file_put_contents($file, $strLog);
        }
    }

    protected function tearDown(): void
    {
        $dir = __DIR__ . '/resources/';
        $file = $dir . 'log.txt';
        if (file_exists($file)) {
            unlink($file);
        }

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
     */
    public function ftruncatestartTest()
    {
        $dir = __DIR__ . '/resources/';
        $file = $dir . 'log.txt';

        $this->assertEquals(true, LogHelper::ftruncatestart($file, 2000));
        $content = file_get_contents($file);

        $truncatedfilecontents = '83;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;'.PHP_EOL;
        $truncatedfilecontents .= '84;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;'.PHP_EOL;
        $truncatedfilecontents .= '85;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;'.PHP_EOL;
        $truncatedfilecontents .= '86;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;'.PHP_EOL;
        $truncatedfilecontents .= '87;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;
        $truncatedfilecontents .= '88;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;
        $truncatedfilecontents .= '89;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;
        $truncatedfilecontents .= '90;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;
        $truncatedfilecontents .= '91;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;
        $truncatedfilecontents .= '92;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;
        $truncatedfilecontents .= '93;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;
        $truncatedfilecontents .= '94;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;
        $truncatedfilecontents .= '95;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;
        $truncatedfilecontents .= '96;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;
        $truncatedfilecontents .= '97;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;
        $truncatedfilecontents .= '98;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;
        $truncatedfilecontents .= '99;uno;due;tre;quattro;cinque;sei;sette;otto;nove;dieci;' . PHP_EOL;

        $this->assertEquals($truncatedfilecontents, $content);
    }
}
