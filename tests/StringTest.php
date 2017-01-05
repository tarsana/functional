<?php namespace Tarsana\UnitTests\Functional;

use Tarsana\Functional as F;

class StringTest extends \Tarsana\UnitTests\Functional\UnitTest {

	public function test_split() {
		$words = F\split(' ');
		$this->assertEquals(['Hello', 'World'], $words('Hello World'));
	}

	public function test_join() {
		$sentence = F\join(' ');
		$this->assertEquals('Hello World', $sentence(['Hello', 'World']));
	}

	public function test_replace() {
		$string = 'a b c d e f';
		$noSpace = F\replace(' ', '');
		$this->assertEquals('abcdef', $noSpace($string));
		$this->assertEquals('cdef', F\replace(['a', 'b', ' '], '', $string));
		$this->assertEquals('xbcdyf', F\replace(['a', 'e', ' '], ['x', 'y', ''], $string));
	}

	public function test_regReplace() {
		$string = 'A12;b_{F}|d';
		$alpha = F\regReplace('/[^a-z]+/i', '');
		$this->assertEquals('AbFd', $alpha($string));
	}

	public function test_upperCase() {
		$this->assertEquals('HELLO', F\upperCase('hello'));
	}

	public function test_lowerCase() {
		$this->assertEquals('hello', F\lowerCase('HeLLO'));
	}

	public function test_camelCase() {
		$this->assertEquals('yesWeCan123', F\camelCase('Yes, we can! 123'));
	}

	public function test_snakeCase() {
		$underscoreCase = F\snakeCase('_');
		$this->assertEquals('i_am_happy', $underscoreCase('IAm-Happy'));
	}

	public function test_startsWith() {
		$http = F\startsWith('http://');
		$this->assertEquals(true, $http('http://gitbub.com'));
		$this->assertEquals(false, $http('gitbub.com'));
	}

	public function test_endsWith() {
		$dotCom = F\endsWith('.com');
		$this->assertEquals(true, $dotCom('http://gitbub.com'));
		$this->assertEquals(false, $dotCom('php.net'));
	}

	public function test_test() {
		$numeric = F\test('/^[0-9.]+$/');
		$this->assertEquals(true, $numeric('123.43'));
		$this->assertEquals(false, $numeric('12a3.43'));
	}

	public function test_match() {
		$numbers = F\match('/[0-9.]+/');
		$this->assertEquals([], $numbers('Hello World'));
		$this->assertEquals(['12', '4', '3'], $numbers('12 is 4 times 3'));
	}

	public function test_occurences() {
		$spaces = F\occurences(' ');
		$this->assertEquals(0, $spaces('Hello'));
		$this->assertEquals(4, $spaces('12 is 4 times 3'));
	}

	public function test_chunks() {
		$names = F\chunks('()""', ' ');
		$this->assertEquals(['Foo', '"Bar Baz"', '(Some other name)'], $names('Foo "Bar Baz" (Some other name)'));
		$groups = F\chunks('(){}', '->');
		$this->assertEquals(['1', '2', '(3->4->5)', '{6->(7->8)}', '9'], $groups('1->2->(3->4->5)->{6->(7->8)}->9'));
	}
}

