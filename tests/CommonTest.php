<?php

use Tarsana\Functional as F;
use Tarsana\Functional\Error;
use Tarsana\Functional\Stream;

class CommonTest extends PHPUnit_Framework_TestCase {

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

    public function test_toString(){
        $this->assertEquals('53', F\toString(53));
        $this->assertEquals('true', F\toString(true));
        $this->assertEquals('false', F\toString(false));
        $this->assertEquals('null', F\toString(null));
        $this->assertEquals('Hello World', F\toString('Hello World'));
        $this->assertEquals('[]', F\toString([]));
        $this->assertEquals('[hi, hello, yo]', F\toString(['hi', 'hello', 'yo']));
        $this->assertEquals('[Object]', F\toString(new \stdClass));
        $this->assertEquals('[Function]', F\toString(function(){}));
        $this->assertEquals('[Resource]', F\toString(fopen('php://temp', 'r')));
        $this->assertEquals('[Error: Ooops]', F\toString(Error::of('Ooops')));
        $data = [
            'object' => Stream::of(null),
            'numbers' => [1, 2, 3],
            'message'
        ];
        $this->assertEquals(
            '[object => Stream(Null), numbers => [1, 2, 3], 0 => message]',
            F\toString($data)
        );
    }

    public function test_s() {
        $this->assertEquals('Hello World !',
            F\s('! World Hello')
            ->then(F\split(' '))
            ->then('array_reverse')
            ->then(F\join(' '))
            ->get()
        );
    }

}
