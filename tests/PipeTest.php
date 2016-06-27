<?php

use Tarsana\Functional as F;

class PipeTest extends PHPUnit_Framework_TestCase {

    protected $hello;
    protected $add;
    protected $sum;

    public function setUp() {
        $this->hello = function() {
            return 'Hello';
        };
        $this->add = function($x, $y) {
            return $x + $y;
        };
        $add = $this->add;
        $this->sum = function($numbers) use ($add) {
            return array_reduce($numbers, $add, 0);
        };
    }

    /**
     * @expectedException Tarsana\Functional\Exceptions\InvalidArgument
     */
    public function testPipeWithoutArg() {
        F\pipe();
    }

    public function testPipeOneFunctionWithoutArgs() {
        $fn = F\pipe($this->hello);
        $this->assertEquals("Hello", $fn());
    }

    public function testPipeOneFunctionWithTwoArgs() {
        $add = F\pipe($this->add);
        $this->assertEquals(11, $add(4, 7));
    }

    public function testPipeTwoFunctions() {
        $lowerHello = F\pipe($this->hello, 'strtolower');
        $this->assertEquals('hello', $lowerHello());

        $addThenDouble = F\pipe($this->add, ['ForTest', 'double']);
        $this->assertEquals(10, $addThenDouble(2, 3));

        $doubleThenSum = F\pipe(ForTest::map(['ForTest', 'double']), $this->sum);
        $this->assertEquals(10, $doubleThenSum([2, 3]));
    }

    public function testPipeInstanceMethod() {
        $upperHello = F\pipe($this->hello, [new ForTest, 'upper']);

        $this->assertEquals('HELLO', $upperHello());
    }

}

class ForTest {
    public static function double($x) {
        return 2 * $x;
    }

    public static function map($fn) {
        return function ($items) use ($fn) {
            return array_map($fn, $items);
        };
    }

    public function upper($text) {
        return strtoupper($text);
    }
}
