<?php namespace Tarsana\UnitTests\Functional;

use Tarsana\Functional as F;

class MathTest extends \Tarsana\UnitTests\Functional\UnitTest {

	public function test_plus() {
		$plusTwo = F\plus(2);
		$this->assertEquals(7, $plusTwo(5));
	}

	public function test_minus() {
		$this->assertEquals(5, F\minus(7, 2));
	}

	public function test_negate() {
		$this->assertEquals(-5, F\negate(5));
		$this->assertEquals(7, F\negate(-7));
	}

	public function test_multiply() {
		$double = F\multiply(2);
		$this->assertEquals(10, $double(5));
	}

	public function test_divide() {
		$this->assertEquals(5, F\divide(10, 2));
	}

	public function test_modulo() {
		$this->assertEquals(1, F\modulo(10, 3));
	}

	public function test_sum() {
		$this->assertEquals(10, F\sum([1, 2, 3, 4]));
		$this->assertEquals(0, F\sum([]));
	}

	public function test_product() {
		$this->assertEquals(24, F\product([1, 2, 3, 4]));
		$this->assertEquals(1, F\product([]));
	}

	public function test_min() {
		$this->assertEquals(1, F\min(1, 3));
		$this->assertEquals(-3, F\min(1, -3));
	}

	public function test_minBy() {
		$this->assertEquals('Hi', F\minBy(F\length(), 'Hello', 'Hi'));
		$this->assertEquals(1, F\minBy('abs', 1, -3));
	}

	public function test_max() {
		$this->assertEquals(3, F\max(1, 3));
		$this->assertEquals(1, F\max(1, -3));
	}

	public function test_maxBy() {
		$this->assertEquals('Hello', F\maxBy(F\length(), 'Hello', 'Hi'));
		$this->assertEquals(-3, F\maxBy('abs', 1, -3));
	}
}

