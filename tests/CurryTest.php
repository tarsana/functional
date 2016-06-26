<?php

use Tarsana\Functional as F;

class CurryTest extends PHPUnit_Framework_TestCase {

    public function testCurryWithoutArg() {
        $fn = F\curry(function(){
            return 'The result';
        });

        $this->assertEquals('The result', $fn());
    }

    public function testCurryWithOneArg() {
        $fn = F\curry(function($x){
            return $x + 1;
        });

        $this->assertEquals(2, $fn(1));
    }

    public function testCurryWithTwoArgs() {
        $add = F\curry(function($x, $y){
            return $x + $y;
        });

        $addFive = $add(5);

        $this->assertEquals(10, $addFive(5));
        $this->assertEquals(10, $add(5, 5));
    }

    public function testCurryWithMultipleArgs() {
        $introduce = F\curry(function($firstName, $lastName, $age, $job){
            return "My name is {$firstName} {$lastName}, I am {$age} years old and my job is {$job}";
        });

        $result = 'My name is Amine Ben hammou, I am 26 years old and my job is Software Engineer';

        $introduceMe = $introduce('Amine', 'Ben hammou');

        $this->assertEquals($result, $introduceMe(26, 'Software Engineer'));
        $this->assertEquals($result, $introduce('Amine', 'Ben hammou', 26, 'Software Engineer'));
    }
}
