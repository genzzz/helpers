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

        $this->assertNotTrue(getenv('TEST'));
        $this->assertSame(getenv('ENV_TEST'), 'test');
        $this->assertNull(env('TEST'));
        $this->assertSame(env('TEST', 'newTest'), 'newTest');
        $this->assertSame(env('ENV_TEST'), 'test');
        $this->assertSame(env('ANOTHER_ENV_TEST', 'newTest'), 'anotherTest');
    }

    public function testEnvConfig()
    {
        $dotenv = Dotenv::createImmutable(__DIR__, 'config.env');
        $dotenv->load();

        $this->assertNotTrue(getenv('TEST'));
        $this->assertSame(getenv('CONFIG_ENV_TEST'), 'test');
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

        $this->assertNotTrue(getenv('TEST'));
        $this->assertSame(getenv('ANOTHER_CONFIG_ENV_TEST'), 'test');
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

    public function testConfigFunction()
    {
        $path = __DIR__ . '/config/';
        if(PHP_OS_FAMILY == 'Windows')
            $path = str_replace("/", "\\", $path);

        putenv("CONFIG_PATH=" . $path);
        $app = config('app');

        $this->assertIsArray($app);
        $this->assertArrayHasKey('key', $app);
        $this->assertArrayHasKey('anotherKey', $app);
        $this->assertSame($app['key'], 'value');
        $this->assertSame($app['anotherKey'], 'anotherValue');

        $path = __DIR__ . '\\config\\anotherConfig\\';
        $app = config('anotherApp', $path);

        $this->assertIsArray($app);
        $this->assertArrayHasKey('newKey', $app);
        $this->assertArrayHasKey('anotherNewKey', $app);
        $this->assertSame($app['newKey'], 'newValue');
        $this->assertSame($app['anotherNewKey'], 'anotherNewValue');
    }
}