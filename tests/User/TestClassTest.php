<?php


namespace tests;

use App\TestClass;
use PHPUnit\Framework\TestCase;
use src\CalcSum;




final class TestClassTest extends TestCase
{
    public function testSum(): void {
        $test_class = new TestClass();
        $this->assertEquals(12, $test_class->sum(6, 4));
    }

}