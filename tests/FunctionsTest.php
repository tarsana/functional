<?php namespace Tarsana\UnitTests\Functional;

use Tarsana\Functional as F;

class FunctionsTest extends \Tarsana\UnitTests\Functional\UnitTest {

	public function test_curry() {
		$add = F\curry(function($x, $y) {
		    return $x + $y;
		});
		$this->assertEquals(3, $add(1, 2));
		$addFive = $add(5); // this is a function
		$this->assertEquals(6, $addFive(1));
		$data = [1, 2, 3, 4, 5];
		$slice = F\curry('array_slice');
		$itemsFrom = $slice($data);
		$this->assertEquals([3, 4, 5], $itemsFrom(2));
		$this->assertEquals([2, 3, 4, 5], $itemsFrom(1, 2));
		// Notice that optional arguments are ignored !
		$polynomial = F\curry(function($a, $b, $c, $x) {
		    return $a * $x * $x + $b * $x + $c;
		});
		$f = $polynomial(0, 2, 1); // 2 * $x + 1
		$this->assertEquals(11, $f(5));
	}

	public function test___() {
		$reduce = F\curry('array_reduce');
		$sum = $reduce(F\__(), F\plus());
		$this->assertEquals(10, $sum([1, 2, 3, 4], 0));
		$polynomial = F\curry(function($a, $b, $c, $x) {
		    return $a * $x * $x + $b * $x + $c;
		});
		$multiplier = $polynomial(0, F\__(), 0, F\__());
		$triple = $multiplier(3);
		$this->assertEquals(15, $triple(5));
		$multipleOfThree = $multiplier(F\__(), 3);
		$this->assertEquals(12, $multipleOfThree(4));
	}

	public function test_apply() {
		$this->assertEquals(5, F\apply('strlen', ['Hello']));
		$replace = F\apply('str_replace');
		$this->assertEquals('Heooo', $replace(['l', 'o', 'Hello']));
	}

	public function test_pipe() {
		$double = function($x) { return 2 * $x; };
		$addThenDouble = F\pipe(F\plus(), $double);
		$this->assertEquals(10, $addThenDouble(2, 3));
	}

	public function test_compose() {
		$double = function($x) { return 2 * $x; };
		$addThenDouble = F\compose($double, F\plus());
		$this->assertEquals(10, $addThenDouble(2, 3));
	}

	public function test_identity() {
		$this->assertEquals('Hello', F\identity('Hello'));
		$this->assertEquals([1, 2, 3], F\identity([1, 2, 3]));
		$this->assertEquals(null, F\identity(null));
	}

	public function test_give() {
		$five = F\give(5);
		$this->assertEquals(5, $five());
		$null = F\give(null);
		$this->assertEquals(null, $null());
	}

	public function test_all() {
		$betweenOneAndTen = F\all(F\lt(1), F\gt(10));
		$this->assertEquals(true, $betweenOneAndTen(5));
		$this->assertEquals(false, $betweenOneAndTen(0));
		$alwaysTrue = F\all();
		$this->assertEquals(true, $alwaysTrue(1));
		$this->assertEquals(true, $alwaysTrue(null));
	}

	public function test_any() {
		$startsOrEndsWith = function($text) {
		    return F\any(F\startsWith($text), F\endsWith($text));
		};
		$test = $startsOrEndsWith('b');
		$this->assertEquals(true, $test('bar'));
		$this->assertEquals(true, $test('bob'));
		$this->assertEquals(false, $test('foo'));
		$alwaysFlase = F\any();
		$this->assertEquals(false, $alwaysFlase(1));
		$this->assertEquals(false, $alwaysFlase(null));
	}

	public function test_complement() {
		$isOdd = function($number) {
		    return 1 == $number % 2;
		};
		$isEven = F\complement($isOdd);
		$this->assertEquals(false, $isEven(5));
		$this->assertEquals(true, $isEven(8));
	}

	public function test_comparator() {
		$users = [
		    ['name' => 'foo', 'age' => 21],
		    ['name' => 'bar', 'age' => 11],
		    ['name' => 'baz', 'age' => 15]
		];
		usort($users, F\comparator(function($a, $b){
		    return $a['age'] < $b['age'];
		}));
		$this->assertEquals(['bar', 'baz', 'foo'], F\map(F\get('name'), $users));
	}
}

