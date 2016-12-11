<?php namespace Tarsana\UnitTests\Functional\Internal;

use Tarsana\Functional as F;

class FunctionsTest extends \Tarsana\UnitTests\Functional\UnitTest {

	public function test__f() {
		$this->assertEquals('Tarsana\Functional\foo', F\_f('foo'));
	}

	public function test__number_of_args() {
		$this->assertEquals(2, F\_number_of_args(function($x, $y){}));
	}

	public function test__is_placeholder() {
		$this->assertEquals(true, F\_is_placeholder(F\__()));
		$this->assertEquals(false, F\_is_placeholder('other thing'));
	}



}

