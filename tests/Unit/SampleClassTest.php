<?php

declare(strict_types=1);

namespace Mustafin\SLL\Tests\Unit;

use Mustafin\SLL\SampleClass;
use Mustafin\SLL\Tests\TestCaseBase;

class SampleClassTest extends TestCaseBase
{
    public function testSampleFunction(): void
    {
        $expected = 'Hello, World!';

        $sampleClassInstance = new SampleClass();

        $result = $sampleClassInstance->helloWorld();

        $this->assertEquals($expected, $result);
    }
}
