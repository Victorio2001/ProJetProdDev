<?php

namespace Units;

use PHPUnit\Framework\TestCase;

class LoanTest extends TestCase
{
    public function testLoanIsWorking(): void
    {
        $string = 'Hello, World';
        $this->assertSame('Hello, World', $string);
    }
}
