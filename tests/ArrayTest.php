<?php

use Tarsana\Functional as F;

class ArrayTest extends PHPUnit_Framework_TestCase {

    public function test_value(){
        $array = [
            'type' => 'Array',
            'length' => 78
        ];
        $array[3] = 'three';
        $typeOf = F\value('type');
        $this->assertEquals('Array', $typeOf($array));
        $this->assertEquals(78, F\value('length', $array));
        $this->assertEquals('three', F\value(3, $array));
    }

    public function test_map(){
        $numbers = [1, 2, 3, 4];
        $doubles = F\map(function($x){ return 2 * $x; });
        $this->assertEquals([2, 4, 6, 8], $doubles($numbers));
    }

    public function test_filter(){
        $array = [1, 'aa', 3, [4, 5]];
        $numeric = F\filter('is_numeric');
        $this->assertEquals([1, 3], $numeric($array));
    }

    public function test_reduce(){
        $array = [1, 2, 3, 4];
        $sum = F\reduce('Tarsana\Functional\plus',0);
        $this->assertEquals(10, $sum($array));
    }

    public function test_each(){
        $array = [1, 2, 3, 4];
        $result = [];
        F\each(function($item) use(&$result){
            $result[] = $item;
        }, $array);
        $this->assertEquals($array, $result);
    }

    public function test_head(){
        $array = [1, 2, 3, 4];
        $this->assertEquals(1, F\head($array));
        $this->assertEquals('H', F\head('Hello'));
        $this->assertEquals(7, F\head([7]));
        $this->assertNull(F\head([]));
        $this->assertEquals('', F\head(''));
    }

    public function test_last(){
        $this->assertEquals(3, F\last([1, 2, 3]));
        $this->assertEquals('o', F\last('Hello'));
        $this->assertEquals(7, F\last([7]));
        $this->assertNull(F\last([]));
        $this->assertEquals('', F\last(''));
    }

    public function test_init(){
        $this->assertEquals([1, 2], F\init([1, 2, 3]));
        $this->assertEquals('Hell', F\init('Hello'));
        $this->assertEquals([], F\init([7]));
        $this->assertEquals([], F\init([]));
        $this->assertEquals('', F\init(''));
    }

    public function test_tail(){
        $this->assertEquals([2, 3], F\tail([1, 2, 3]));
        $this->assertEquals('ello', F\tail('Hello'));
        $this->assertEquals([], F\tail([7]));
        $this->assertEquals([], F\tail([]));
        $this->assertEquals('', F\tail(''));
    }

    public function test_reverse(){
        $this->assertEquals('olleH', F\reverse('Hello'));
        $this->assertEquals([3, 2, 1], F\reverse([1, 2, 3]));
    }

    public function test_length(){
        $this->assertEquals(3, F\length([1, 2, 3]));
        $this->assertEquals(5, F\length('Hello'));
    }
}
