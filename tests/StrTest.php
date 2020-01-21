<?php
use PHPUnit\Framework\TestCase;
use Genzzz\Helpers\Str;

class StrTest extends TestCase
{
    public function test_after_function()
    {
        $string = 'Genci Shabani';
        $this->assertSame(Str::after($string, 'Genci'), ' Shabani');
        $this->assertSame(Str::after($string, 'Genci '), 'Shabani');
        $this->assertNotSame(Str::after($string, 'Genci'), 'Shabani');
    }

    public function test_before_function()
    {
        $string = 'Genci Shabani';
        $this->assertSame(Str::before($string, 'Shabani'), 'Genci ');
        $this->assertSame(Str::before($string, ' Shabani'), 'Genci');
        $this->assertNotSame(Str::before($string, 'Shabani'), 'Genci');
    }

    public function test_camel_function()
    {
        $this->assertSame(Str::camel('Genci Shabani'), 'genciShabani');
        $this->assertSame(Str::camel('test_test'), 'testTest');
        $this->assertSame(Str::camel('test-test'), 'testTest');
    }
}