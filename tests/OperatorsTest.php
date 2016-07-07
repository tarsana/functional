<?php

use Tarsana\Functional as F;
use Tarsana\Functional\Error;

class OperatorsTest extends PHPUnit_Framework_TestCase {
    public function test_and(){
        $this->assertTrue(F\and_(true, true));
        $this->assertFalse(F\and_(true, false));
        $this->assertFalse(F\and_(false, true));
        $this->assertFalse(F\and_(false, false));
    }

    public function test_or(){
        $this->assertTrue(F\or_(true, true));
        $this->assertTrue(F\or_(true, false));
        $this->assertTrue(F\or_(false, true));
        $this->assertFalse(F\or_(false, false));
    }

    public function test_not(){
        $this->assertTrue(F\not(false));
        $this->assertFalse(F\not(true));
    }

    public function test_eq(){
        $this->assertTrue(F\eq('hi', 'hi'));
        $this->assertTrue(F\eq(13, 13));
        $this->assertTrue(F\eq('1', 1));
        $this->assertFalse(F\eq('hi', 'yo'));
        $this->assertFalse(F\eq(1, 'yo'));
    }

    public function test_notEq(){
        $this->assertTrue(F\notEq(1, 'yo'));
        $this->assertTrue(F\notEq('hi', 'yo'));
        $this->assertFalse(F\notEq('hi', 'hi'));
        $this->assertFalse(F\notEq('1', 1));
    }

    public function test_eqq(){
        $this->assertTrue(F\eqq('hi', 'hi'));
        $this->assertTrue(F\eqq(13, 13));
        $this->assertFalse(F\eqq('1', 1));
        $this->assertFalse(F\eqq('hi', 'yo'));
        $this->assertFalse(F\eqq(1, 'yo'));
    }

    public function test_notEqq(){
        $this->assertTrue(F\notEqq(1, 'yo'));
        $this->assertTrue(F\notEqq('hi', 'yo'));
        $this->assertFalse(F\notEqq('hi', 'hi'));
        $this->assertTrue(F\notEqq('1', 1));
    }

    public function test_lt(){
        $this->assertTrue(F\lt(4, 6));
        $this->assertFalse(F\lt(4, 4));
        $this->assertFalse(F\lt(4, 2));
    }

    public function test_lte(){
        $this->assertTrue(F\lte(4, 6));
        $this->assertTrue(F\lte(4, 4));
        $this->assertFalse(F\lte(4, 2));
    }

    public function test_gt(){
        $this->assertTrue(F\gt(6, 4));
        $this->assertFalse(F\gt(4, 4));
        $this->assertFalse(F\gt(2, 4));
    }

    public function test_gte(){
        $this->assertTrue(F\gte(6, 4));
        $this->assertTrue(F\gte(4, 4));
        $this->assertFalse(F\gte(2, 4));
    }

    public function test_type(){
        $this->assertEquals(F\type(null), 'Null');
        $this->assertEquals(F\type(true), 'Boolean');
        $this->assertEquals(F\type(false), 'Boolean');
        $this->assertEquals(F\type('Hello World'), 'String');
        $this->assertEquals(F\type(1234), 'Number');
        $this->assertEquals(F\type('123'), 'String');
        $this->assertEquals(F\type(function($x) {return $x;}), 'Function');
        $this->assertEquals(F\type(new \stdClass), 'Object');
        $this->assertEquals(F\type(['name' => 'Foo', 'age' => 21]), 'ArrayObject');
        $this->assertEquals(F\type(['Hello', 'World', 123, true]), 'List');
        $this->assertEquals(F\type(['name' => 'Foo', 'Hello', 'Mixed']), 'Array');
        $this->assertEquals(F\type(fopen('php://temp', 'r')), 'Resource');
        $this->assertEquals(F\type(Error::of('Ooops !')), 'Error');
    }

}
