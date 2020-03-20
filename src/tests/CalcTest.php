<?php


final class CalcTest extends \PHPUnit\Framework\TestCase
{
    public function testSum() {
        $this->assertEquals('10', CalcSum::sum(6, 4));
    }

}