<?php
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_suma_basica()
    {
        $this->assertEquals(5, 3 + 2);
    }
}