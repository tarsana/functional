<?php

use Tarsana\Functional as F;

class ApplyTest extends PHPUnit_Framework_TestCase {

    public function testApplyWithoutArg() {
        $fn = function(){
            return 'The result';
        };

        $this->assertEquals('The result', F\apply($fn, []));
    }

    public function testApplyWithOneArg() {
        $fn = function($x){
            return $x + 1;
        };

        $this->assertEquals(2, F\apply($fn, [1]));
    }

    public function testApplyIsCurried() {
        $fn = function($x){
            return $x + 1;
        };

        $applyFn = F\apply($fn);

        $this->assertEquals(2, $applyFn([1]));
    }

    public function testApplyWithMultipleArgs() {
        $introduce = function($firstName, $lastName, $age, $job){
            return "My name is {$firstName} {$lastName}, I am {$age} years old and my job is {$job}";
        };

        $result = 'My name is Amine Ben hammou, I am 26 years old and my job is Software Engineer';

        $this->assertEquals($result, F\apply($introduce, ['Amine', 'Ben hammou', 26, 'Software Engineer']));
    }
}
