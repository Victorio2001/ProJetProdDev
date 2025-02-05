<?php

namespace Units;

use LoanTest;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testLoanIsWorking(): void
    {
        $string = 'Hello, World';
        $this->assertSame('Hello, World', $string);
    }
}
