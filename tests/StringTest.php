<?php

use Tarsana\Functional as F;
use Tarsana\Functional\Error;
use Tarsana\Functional\Stream;

class StringTest extends PHPUnit_Framework_TestCase {

    public function test_split(){
        $words = F\split(' ');
        $this->assertEquals(['foo', 'bar', 'baz'], $words('foo bar baz'));
        $this->assertEquals(['foo'], $words('foo'));
        $this->assertEquals([''], $words(''));
    }

    public function test_join(){
        $join = F\join(', ');
        $this->assertEquals('foo, bar, baz', $join(['foo', 'bar', 'baz']));
        $this->assertEquals('foo', $join(['foo']));
        $this->assertEquals('', $join([]));
    }

    public function test_replace(){
        $noSpace = F\replace(' ', '');
        $this->assertEquals('abcdef', $noSpace('a bc d   e f'));
        $this->assertEquals('bcdf', F\replace([' ', 'a', 'e'], '', 'a bc d   e f'));
        $this->assertEquals('cj', F\replace(['a', 'b', 'i'], ['b', 'c', 'j'], 'ai'));
    }

    public function test_regReplace(){
        $alpha = F\regReplace('/[^a-z]+/i', '');
        $this->assertEquals('AbFd', $alpha('A12;b_{F}|d'));
    }

    public function test_upperCase(){
        $this->assertEquals('HI 123 !', F\upperCase('hi 123 !'));
    }

    public function test_lowerCase(){
        $this->assertEquals('hi 123 !', F\lowerCase('Hi 123 !'));
    }

    public function test_camelCase(){
        $this->assertEquals('yesWeCan123', F\camelCase('Yes, we can! 123'));
    }

    public function test_snakeCase(){
        $snakeCase = F\snakeCase('-');
        $this->assertEquals('yes-we-can-123', $snakeCase('Yes, we can! 123'));
        $this->assertEquals('yes_we_can_123', F\snakeCase('_', 'yesWeCan123'));
    }

    public function test_startsWith(){
        $http = F\startsWith('http://');
        $this->assertTrue($http('http://github.com'));
        $this->assertTrue(F\startsWith('the same', 'the same'));
        $this->assertFalse($http('github.com'));
        $this->assertFalse(F\startsWith('something very long', 'small thing'));
        $this->assertFalse(F\startsWith('something very long', 'something very'));
    }

    public function test_endsWith(){
        $com = F\endsWith('.com');
        $this->assertTrue($com('http://github.com'));
        $this->assertTrue(F\endsWith('the same', 'the same'));
        $this->assertFalse($com('php.net'));
        $this->assertFalse(F\endsWith('something very long', 'small thing'));
        $this->assertFalse(F\endsWith('something very long', 'very long'));
    }

    public function test_test(){
        $numeric = F\test('/^[0-9.]+$/');
        $this->assertTrue($numeric('467838.3578'));
        $this->assertFalse($numeric('4678a8.3578'));
    }

    public function test_match(){
        $numbers = F\match('/[0-9]+/');
        $this->assertEquals([], $numbers('foo bar'));
        $this->assertEquals(['12', '3', '09'], $numbers('12 fo3o bar09'));
    }

    public function test_occurences(){
        $spaces = F\occurences(' ');
        $this->assertEquals(0, $spaces('foo'));
        $this->assertEquals(4, $spaces('foo  bar baz '));
    }

    public function test_chunks(){
        $groups = F\chunks('(){}', ',');
        $this->assertEquals(
            ['1', '2', '(3,4,5)', '{6,(7,8)}', '9'],
            $groups('1,2,(3,4,5),{6,(7,8)},9')
        );
        $names = F\chunks('""()', ' ');
        $this->assertEquals(
            ['Foo', '"Bar Baz"', '(Some other name)'],
            $names('Foo "Bar Baz" (Some other name)')
        );
    }

}
