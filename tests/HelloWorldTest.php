<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class HelloWorldTest extends TestCase
{
    public function testCanCompareStrings(): void
    {
        $string = 'Hello, World';
        $this->assertSame('Hello, World', $string);
    }

    public function testCanCompareIntegers(): void
    {
        $int = 1;
        $this->assertSame(1, $int);
    }

    public function testCanCompareArrays(): void
    {
        $array = [1, 2, 3];
        $this->assertSame([1, 2, 3], $array);
    }
}
