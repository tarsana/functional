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
}

