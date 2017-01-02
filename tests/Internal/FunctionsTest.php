<?php namespace Tarsana\UnitTests\Functional\Internal;

use Tarsana\Functional as F;

class FunctionsTest extends \Tarsana\UnitTests\Functional\UnitTest {

	public function test__f() {
		$this->assertEquals('Tarsana\Functional\foo', F\_f('foo'));
	}

	public function test__number_of_args() {
		$this->assertEquals(2, F\_number_of_args(function($x, $y){}));
	}

	public function test__apply() {
		$sum = function() {
		    return F\sum(func_get_args());
		};
		$this->assertEquals(15, F\_apply($sum, [1, 2, 3, 4, 5]));
		$this->assertEquals(21, F\_apply($sum, [1, 2, 3, 4, 5, 6]));
	}

	public function test__is_placeholder() {
		$this->assertEquals(true, F\_is_placeholder(F\__()));
		$this->assertEquals(false, F\_is_placeholder('other thing'));
	}

	public function test__curry_two() {
		$add = F\_curry_two(function($x, $y) {
		    return $x + $y;
		});
		$addOne = $add(1, F\__());
		$this->assertEquals(3, $addOne(2));
	}

	public function test__curry_three() {
		$add = F\_curry_three(function($x, $y, $z) {
		    return $x + $y + $z;
		});
		$this->assertEquals(6, $add(1, 2, 3));
		$f = $add(F\__(), 2, F\__());
		$this->assertEquals(6, $f(1, 3));
		$g = $add(1, F\__(), 3);
		$this->assertEquals(6, $g(2));
		$h = $add(F\__(), F\__(), 3);
		$this->assertEquals(6, $h(1, 2));
		$i = $add(F\__(), 2, 3);
		$this->assertEquals(6, $i(1));
	}

	public function test__curry_n() {
		$polynomial = F\_curry_n(function($a, $b, $c, $x) {
		    return $a * $x * $x + $b * $x + $c;
		}, 4);
		$linear = $polynomial(0);
		$this->assertEquals(11, $linear(2, 1, 5));
	}

	public function test__merge_args() {
		$given = [F\__(), 2];
		$args = [1, 3];
		$newArgs = F\_merge_args($given, $args, 4);
		$this->assertEquals((object) ['args' => [1, 2, 3, F\__()], 'placeholders' => 1], $newArgs);
	}
}

