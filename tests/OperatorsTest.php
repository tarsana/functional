<?php namespace Tarsana\UnitTests\Functional;

use Tarsana\Functional as F;

class OperatorsTest extends \Tarsana\UnitTests\Functional\UnitTest {

	public function test_and_() {
		$isTrue = F\and_(true);
		$this->assertEquals(false, $isTrue(false));
		$this->assertEquals(true, $isTrue(true));
	}

	public function test_or_() {
		$isTrue = F\or_(false);
		$this->assertEquals(false, $isTrue(false));
		$this->assertEquals(true, $isTrue(true));
	}

	public function test_not() {
		$this->assertEquals([false, true, false], F\map(F\not(), [true, false, true]));
	}

	public function test_eq() {
		$this->assertEquals(true, F\eq('10', 10));
	}

	public function test_notEq() {
		$this->assertEquals(true, F\notEq('Hi', 'Hello'));
	}

	public function test_eqq() {
		$this->assertEquals(false, F\eqq(10, '10'));
	}

	public function test_notEqq() {
		$this->assertEquals(true, F\notEqq(10, '10'));
	}

	public function test_equals() {
		$a = (object) ['a' => 1, 'b' => (object) ['c' => 'Hello'], 'd' => false];
		$b = (object) ['a' => 1, 'b' => (object) ['c' => 'Hi'], 'd' => false];
		$c = (object) ['a' => 1, 'b' => ['c' => 'Hello'], 'd' => false];
		// should have the same type
		$this->assertEquals(false, F\equals(5, '5'));
		$this->assertEquals(true, F\equals([1, 2, 3], [1, 2, 3]));
		// should have the same order
		$this->assertEquals(false, F\equals([1, 3, 2], [1, 2, 3]));
		$this->assertEquals(false, F\equals($a, $b));
		$this->assertEquals(false, F\equals($a, $c));
		$b->b->c = 'Hello';
		$this->assertEquals(true, F\equals($a, $b));
	}

	public function test_lt() {
		$this->assertEquals(true, F\lt(3, 5));
		$this->assertEquals(false, F\lt(5, 5));
	}

	public function test_lte() {
		$this->assertEquals(true, F\lte(3, 5));
		$this->assertEquals(true, F\lte(5, 5));
	}

	public function test_gt() {
		$this->assertEquals(true, F\gt(5, 3));
		$this->assertEquals(false, F\gt(5, 5));
	}

	public function test_gte() {
		$this->assertEquals(true, F\gte(5, 3));
		$this->assertEquals(true, F\gte(5, 5));
	}
}

