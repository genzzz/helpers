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

    public function testEnvDefault()
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $this->assertNull(env('TEST'));
        $this->assertSame(env('TEST', 'newTest'), 'newTest');
        $this->assertSame(env('ENV_TEST'), 'test');
        $this->assertSame(env('ANOTHER_ENV_TEST', 'newTest'), 'anotherTest');
    }

    public function testEnvConfig()
    {
        $dotenv = Dotenv::createImmutable(__DIR__, 'config.env');
        $dotenv->load();

        $this->assertNull(env('TEST'));
        $this->assertSame(env('TEST', 'newTest'), 'newTest');

        // get values also from .env
        $this->assertSame(env('ENV_TEST'), 'test');
        $this->assertSame(env('ANOTHER_ENV_TEST', 'newTest'), 'anotherTest');

        // get values from config.env
        $this->assertSame(env('CONFIG_ENV_TEST'), 'test');
        $this->assertSame(env('CONFIG_ANOTHER_ENV_TEST', 'bestTest'), 'anotherTest');
    }

    public function testEnvAnotherConfig()
    {
        $dotenv = Dotenv::createImmutable(__DIR__, 'anotherconfig.env');
        $dotenv->load();

        $this->assertNull(env('TEST'));
        $this->assertSame(env('TEST', 'newTest'), 'newTest');

        // get values also from .env
        $this->assertSame(env('ENV_TEST'), 'test');
        $this->assertSame(env('ANOTHER_ENV_TEST', 'newTest'), 'anotherTest');

        // get values from config.env
        $this->assertSame(env('CONFIG_ENV_TEST'), 'test');
        $this->assertSame(env('CONFIG_ANOTHER_ENV_TEST', 'bestTest'), 'anotherTest');

        // get values from anotherconfig.env
        $this->assertSame(env('ANOTHER_CONFIG_ENV_TEST'), 'test');
        $this->assertSame(env('ANOTHER_CONFIG_ANOTHER_ENV_TEST', 'stillAnotherTest'), 'anotherTest');
    }

    public function testPathProvider()
    {
        $path = path(__DIR__);
        $this->assertIsString($path);

        define('LARAPRESS_PATH', $path);
        $this->assertSame(LARAPRESS_PATH, $path);
    }

    /**
     * @depends testPathProvider
     */
    public function testConfigFunction()
    {
        $app = config('app');

        $this->assertIsArray($app);
        $this->assertArrayHasKey('key', $app);
        $this->assertArrayHasKey('anotherKey', $app);
        $this->assertSame($app['key'], 'value');
        $this->assertSame($app['anotherKey'], 'anotherValue');

        $app = config('anotherConfig/anotherApp', LARAPRESS_PATH . '/config/');

        $this->assertIsArray($app);
        $this->assertArrayHasKey('newKey', $app);
        $this->assertArrayHasKey('anotherNewKey', $app);
        $this->assertSame($app['newKey'], 'newValue');
        $this->assertSame($app['anotherNewKey'], 'anotherNewValue');

        $app = config('test');

        $this->assertNull($app);
    }

    /**
     * @depends testPathProvider
     */
    public function testViewFunction()
    {
        $this->expectOutputString('<h1>Test</h1>');

        view('test');
    }

    /**
     * @depends testPathProvider
     */
    public function testViewFunctionWithData()
    {
        $name = 'John Doe';

        $this->expectOutputString('<h1>Hello John Doe</h1>');

        view('data', compact('name'));
    }
}