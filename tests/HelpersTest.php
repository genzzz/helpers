<?php

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;

class HelpersTest extends TestCase
{
    public function fasleProvider()
    {
        return [
            [null, '', array()]
        ];
    }

    public function trueProvider()
    {
        return [
            ['test', 1, 2, true, false, array(1, 2), array('key' => 'value')]
        ];
    }

    /**
     * @dataProvider fasleProvider
     */
    public function testBlankWithFalseValues($value)
    {
        $this->assertTrue(blank($value));
    }

    /**
     * @dataProvider trueProvider
     */
    public function testBlankWithTrueValues($value)
    {
        $this->assertFalse(blank($value));
    }

    /**
     * @dataProvider fasleProvider
     */
    public function testFilledkWithFalseValues($value)
    {
        $this->assertFalse(filled($value));
    }

    /**
     * @dataProvider trueProvider
     */
    public function testFilledkWithTrueValues($value)
    {
        $this->assertTrue(filled($value));
    }
}