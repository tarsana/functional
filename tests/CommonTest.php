<?php namespace Tarsana\UnitTests\Functional;

use Tarsana\Functional as F;

class CommonTest extends \Tarsana\UnitTests\Functional\UnitTest {

	public function test_type() {
		$this->assertEquals('Null', F\type(null));
		$this->assertEquals('Boolean', F\type(true));
		$this->assertEquals('Boolean', F\type(false));
		$this->assertEquals('String', F\type('Hello World'));
		$this->assertEquals('Number', F\type(1234));
		$this->assertEquals('String', F\type('123'));
		$this->assertEquals('Function', F\type(function($x) {return $x;}));
		$this->assertEquals('Object', F\type(new \stdClass));
		$this->assertEquals('Array', F\type(['name' => 'Foo', 'age' => 21]));
		$this->assertEquals('List', F\type(['Hello', 'World', 123, true]));
		$this->assertEquals('Array', F\type(['name' => 'Foo', 'Hello', 'Mixed']));
		$this->assertEquals('Resource', F\type(fopen('php://temp', 'w')));
		$this->assertEquals('Error', F\type(F\Error::of('Ooops !')));
		$this->assertEquals('Stream', F\type(F\Stream::of('Hello')));
		// Anything else is 'Unknown'
	}

	public function test_is() {
		$isNumber = F\is('Number');
		$this->assertEquals(true, $isNumber(5));
		$this->assertEquals(false, $isNumber('5'));
		$this->assertEquals(true, F\is('Any', '5'));
		$this->assertEquals(true, F\is('Any', [1, 2, 3]));
	}

	public function test_toString() {
		$this->assertEquals('53', F\toString(53));
		$this->assertEquals('true', F\toString(true));
		$this->assertEquals('false', F\toString(false));
		$this->assertEquals('null', F\toString(null));
		$this->assertEquals('"Hello World"', F\toString('Hello World'));
		$this->assertEquals('[]', F\toString([]));
		$this->assertEquals('{}', F\toString(new \stdClass));
		$this->assertEquals('[Function]', F\toString(function(){}));
		$this->assertEquals('[Error: Ooops]', F\toString(F\Error::of('Ooops')));
		$this->assertEquals('[Stream of String]', F\toString(F\Stream::of('Hello')));
		$this->assertEquals('[Resource]', F\toString(fopen('php://temp', 'r')));
		$this->assertEquals('["hi", "hello", "yo"]', F\toString(['hi', 'hello', 'yo']));
		$this->assertEquals('{object: null, numbers: [1, 2, 3], 0: "message"}', F\toString([
		    'object' => null,
		    'numbers' => [1, 2, 3],
		    'message'
		]));
	}

	public function test_s() {
		$s = F\s('! World Hello')
		    ->split(' ')
		    ->reverse()
		    ->join(' ');
		$this->assertEquals('Hello World !', $s->result());
	}
}

