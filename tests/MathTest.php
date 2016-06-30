<?php

use Tarsana\Functional as F;

class MathTest extends PHPUnit_Framework_TestCase {

    public function test_plus() {
        $this->assertEquals(12, F\plus(5, 7));
        $increment = F\plus(1);
        $this->assertEquals(11, $increment(10));
    }

    public function test_minus() {
        $this->assertEquals(-2, F\minus(5, 7));
        $decrement = F\minus(F\__(), 1);
        $this->assertEquals(10, $decrement(11));
    }

    public function test_multiply() {
        $this->assertEquals(12, F\multiply(3, 4));
        $doubles = F\multiply(2);
        $this->assertEquals(6, $doubles(3));
    }

    public function test_divide() {
        $this->assertEquals(3, F\divide(12, 4));
        $halfOf = F\divide(F\__(), 2);
        $this->assertEquals(5, $halfOf(10));
    }

    public function test_negate() {
        $this->assertEquals(3, F\negate(-3));
        $this->assertEquals(-5, F\negate(5));
    }

    public function test_modulo() {
        $this->assertEquals(2, F\modulo(14, 4));
        $m = F\modulo(10);
        $this->assertEquals(1, $m(3));
    }

    public function test_sum() {
        $this->assertEquals(10, F\sum([1, 2, 3, 4]));
        $this->assertEquals(0, F\sum([]));
    }

    public function test_product() {
        $this->assertEquals(12, F\product([2, 2, 3]));
        $this->assertEquals(1, F\product([]));
    }

}
