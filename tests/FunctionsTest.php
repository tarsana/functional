<?php

use Tarsana\Functional as F;

class FunctionsTest extends PHPUnit_Framework_TestCase {

    protected $hello;
    protected $add;
    protected $sum;

    public function setUp() {
        $this->hello = function() {
            return 'Hello';
        };
        $this->add = function($x, $y) {
            return $x + $y;
        };
        $add = $this->add;
        $this->sum = function($numbers) use ($add) {
            return array_reduce($numbers, $add, 0);
        };
    }

    /**
     * @expectedException Tarsana\Functional\Exceptions\InvalidArgument
     */
    public function test_pipe_without_arg() {
        F\pipe();
    }

    public function test_pipe_one_function_without_args() {
        $fn = F\pipe($this->hello);
        $this->assertEquals("Hello", $fn());
    }

    public function test_pipe_one_function_with_two_args() {
        $add = F\pipe($this->add);
        $this->assertEquals(11, $add(4, 7));
    }

    public function test_pipe_two_functions() {
        $lowerHello = F\pipe($this->hello, 'strtolower');
        $this->assertEquals('hello', $lowerHello());

        $addThenDouble = F\pipe($this->add, ['ForTest', 'double']);
        $this->assertEquals(10, $addThenDouble(2, 3));

        $doubleThenSum = F\pipe(ForTest::map(['ForTest', 'double']), $this->sum);
        $this->assertEquals(10, $doubleThenSum([2, 3]));
    }

    public function test_pipe_instance_method() {
        $upperHello = F\pipe($this->hello, [new ForTest, 'upper']);

        $this->assertEquals('HELLO', $upperHello());
    }

    public function test_curry_without_arg() {
        $fn = F\curry(function(){
            return 'The result';
        });

        $this->assertEquals('The result', $fn());
    }

    public function test_curry_with_one_arg() {
        $fn = F\curry(function($x){
            return $x + 1;
        });

        $this->assertEquals(2, $fn(1));
    }

    public function test_curry_with_two_args() {
        $add = F\curry(function($x, $y){
            return $x + $y;
        });

        $addFive = $add(5);

        $this->assertEquals(10, $addFive(5));
        $this->assertEquals(10, $add(5, 5));
    }

    public function test_curry_with_multiple_args() {
        $introduce = F\curry(function($firstName, $lastName, $age, $job){
            return "My name is {$firstName} {$lastName}, I am {$age} years old and my job is {$job}";
        });

        $result = 'My name is Amine Ben hammou, I am 26 years old and my job is Software Engineer';

        $introduceMe = $introduce('Amine', 'Ben hammou');

        $this->assertEquals($result, $introduceMe(26, 'Software Engineer'));
        $this->assertEquals($result, $introduce('Amine', 'Ben hammou', 26, 'Software Engineer'));
    }

    public function test_curry_function_using_func_get_args() {
        $fn = function($x, $y) {
            return implode(', ', func_get_args());
        };

        $curried = F\curry($fn);

        $curriedOne = $curried('foo');

        $this->assertEquals('foo, bar', $fn('foo', 'bar'));
        $this->assertEquals('foo, bar', $curried('foo', 'bar'));
        $this->assertEquals('foo, bar', $curriedOne('bar'));
    }

    public function test_apply_without_arg() {
        $fn = function(){
            return 'The result';
        };

        $this->assertEquals('The result', F\apply($fn, []));
    }

    public function test_apply_with_one_arg() {
        $fn = function($x){
            return $x + 1;
        };

        $this->assertEquals(2, F\apply($fn, [1]));
    }

    public function test_apply_is_curried() {
        $fn = function($x){
            return $x + 1;
        };

        $applyFn = F\apply($fn);

        $this->assertEquals(2, $applyFn([1]));
    }

    public function test_apply_with_multiple_args() {
        $introduce = function($firstName, $lastName, $age, $job){
            return "My name is {$firstName} {$lastName}, I am {$age} years old and my job is {$job}";
        };

        $result = 'My name is Amine Ben hammou, I am 26 years old and my job is Software Engineer';

        $this->assertEquals($result, F\apply($introduce, ['Amine', 'Ben hammou', 26, 'Software Engineer']));
    }

}

class ForTest {
    public static function double($x) {
        return 2 * $x;
    }

    public static function map($fn) {
        return function ($items) use ($fn) {
            return array_map($fn, $items);
        };
    }

    public function upper($text) {
        return strtoupper($text);
    }
}
