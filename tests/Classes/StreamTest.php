<?php namespace Tarsana\UnitTests\Functional\Classes;

use Tarsana\Functional as F;
use Tarsana\Functional\Stream;
use Tarsana\UnitTests\Functional\UnitTest;

class StreamTest extends UnitTest {

    public function fails() {
        $this->assertTrue(false);
    }

    public function test_it_gets_data_type_without_operations() {
        $this->assertAll([
            ['Null',     Stream::of(null)->type()],
            ['Boolean',  Stream::of(true)->type()],
            ['Number',   Stream::of(5.2)->type()],
            ['String',   Stream::of('Foo')->type()],
            ['Resource', Stream::of(fopen('php://memory', "r"))->type()],
            ['Function', Stream::of(F\map())->type()],
            ['List',     Stream::of([1, 2, 3])->type()],
            ['Array',    Stream::of(['foo' => 'bar'])->type()],
            ['Object',   Stream::of((object)['foo' => 'bar'])->type()]
        ]);
    }

    public function test_it_gets_data_without_transformations() {
        $this->assertAll([
            ['data',    Stream::of('data')->result()],
            [5.1,       Stream::of(5.1)->result()],
            [[1, 2, 3], Stream::of([1, 2, 3])->result()],
        ]);
    }

    public function test_it_adds_operation_from_function_name() {
        Stream::operation('size', 'List -> Number', 'count');
        $this->assertAll([
            [3, Stream::of([1, 2, 3])->size()->result()],
            [0, Stream::of([])->size()->result()]
        ]);
        Stream::removeOperations('size');
    }

    public function test_it_adds_operation_from_closure() {
        Stream::operation('increment', 'Number -> Number', function($a) {return $a + 1;});
        $this->assertAll([
            [3, Stream::of(2)->increment()->result()],
            [0, Stream::of(-1)->increment()->result()]
        ]);
        Stream::removeOperations('increment');
    }

    public function test_it_checks_if_operation_is_defined() {
        $this->assertFalse(Stream::hasOperation('foo'));
        Stream::operation('foo', 'List -> Number', 'count');
        $this->assertTrue(Stream::hasOperation('foo'));
        Stream::removeOperations('foo');
    }

    public function test_it_removes_operation() {
        Stream::operation('foo', 'List -> Number', 'count');
        Stream::operation('bar', 'List -> Number', 'count');
        $this->assertTrue(Stream::hasOperation('foo'));
        $this->assertTrue(Stream::hasOperation('bar'));

        Stream::removeOperations('foo');
        $this->assertFalse(Stream::hasOperation('foo'));
        $this->assertTrue(Stream::hasOperation('bar'));
        Stream::removeOperations('bar');
    }

    public function test_it_uses_operation_name_if_function_is_missing() {
        Stream::operation('count', 'List -> Number');
        $this->assertAll([
            [3, Stream::of([1, 2, 3])->count()->result()],
            [0, Stream::of([])->count()->result()]
        ]);
        Stream::removeOperations('count');
    }

    public function test_it_accepts_union_types_in_signatures() {
        Stream::operation('count', 'List|Array -> Number');
        $this->assertAll([
            [3, Stream::of([1, 2, 3])->count()->result()],
            [2, Stream::of(['name' => 'foo', 'age' => 11])->count()->result()]
        ]);
        Stream::removeOperations('count');
    }

    public function test_it_accepts_the_type_any_in_signatures() {
        Stream::operation('text', 'Any -> String', F\toString());
        $this->assertAll([
            ['3', Stream::of(3)->text()->result()],
            ['"Hi"', Stream::of('Hi')->text()->result()],
            ['["Hi", 5]', Stream::of(['Hi', 5])->text()->result()]
        ]);
        Stream::removeOperations('text');
    }

    /**
     * @expectedException Tarsana\Functional\Error
     * @expectedExceptionMessage Stream: unknown callable 'bar'
     */
    public function test_it_throws_exception_when_adding_unknown_callable() {
        Stream::operation('foo', 'Number -> Number', 'bar');
    }

    /**
     * @expectedException Tarsana\Functional\Error
     * @expectedExceptionMessage Stream: invalid signature 'List, Number' it should follow the syntax 'TypeArg1 -> TypeArg2 -> ... -> ReturnType' and types to use are Boolean, Number, String, Resource, Function, List, Array, Object, Any
     */
    public function test_it_throws_exception_when_signature_contains_wrong_caracters() {
        Stream::operation('size', 'List, Number', 'count');
    }

    /**
     * @expectedException Tarsana\Functional\Error
     * @expectedExceptionMessage Stream: invalid signature '[a] -> Number' it should follow the syntax 'TypeArg1 -> TypeArg2 -> ... -> ReturnType' and types to use are Boolean, Number, String, Resource, Function, List, Array, Object
     */
    public function test_it_throws_exception_when_signature_contains_invalid_types() {
        Stream::operation('size', '[a] -> Number', 'count');
    }

    /**
     * @expectedException Tarsana\Functional\Error
     * @expectedExceptionMessage Stream: invalid signature 'Number' it should follow the syntax 'TypeArg1 -> TypeArg2 -> ... -> ReturnType' and types to use are Boolean, Number, String, Resource, Function, List, Array, Object
     */
    public function test_it_throws_exception_when_function_takes_no_arguments() {
        Stream::operation('size', 'Number', 'count');
    }

    public function test_it_throws_exception_when_adding_duplicated_operation() {
        Stream::operation('size', 'List -> Number', 'count');
        Stream::operation('size', 'String -> Number', 'strlen');
        Stream::operation('size', 'String -> Number', 'strlen');
        try {
            Stream::of(123);
            $this->fails();
        } catch (\Exception $e) {
            $this->assertError($e, "Stream: signatures of the operation 'size' are duplicated or ambiguous");
        }
        Stream::removeOperations('size');
    }

    public function test_it_applies_operation_with_single_argument_and_single_signature() {
        $increment = function ($x) {
            return $x + 1;
        };
        $square = function ($x) {
            return $x * $x;
        };
        Stream::operation('increment', 'Number -> Number', $increment);
        Stream::operation('square', 'Number -> Number', $square);
        $s = Stream::of(4);
        $s = $s->square();
        $this->assertEquals(16, $s->result());
        $s = $s->increment()->increment();
        $this->assertEquals(18, $s->result());

        Stream::removeOperations('increment', 'square');
    }

    public function test_it_applies_operation_with_single_argument_and_multiple_signatures() {
        $size = function($listOrString) {
            if (is_string($listOrString))
                return strlen($listOrString);
            return count($listOrString);
        };

        Stream::operation('size', 'List|Array|String -> Number', $size);

        $this->assertAll([
            [5, Stream::of('Hello')->size()->result()],
            [3, Stream::of(['foo', 'bar', 'baz'])->size()->result()],
            [2, Stream::of(['name' => 'foo', 'age' => 11])->size()->result()]
        ]);

        Stream::removeOperations('size');
    }

    public function test_it_applies_operation_with_multiple_arguments_and_single_signature() {
        $add = function ($x, $y) {
            return $x + $y;
        };
        $mult = function ($x, $y) {
            return $x * $y;
        };

        Stream::operation('add', 'Number -> Number -> Number', $add);
        Stream::operation('mult', 'Number -> Number -> Number', $mult);
        Stream::operation('repeat', 'String -> Number -> Number', 'str_repeat');

        $this->assertAll([
            ['foofoofoo', Stream::of(1)->mult(3)->repeat('foo')->result()],
            [15,          Stream::of(1)->add(4)->mult(3)->result()]
        ]);

        Stream::removeOperations('add', 'mult', 'repeat');
    }

    public function test_it_applies_operation_with_multiple_arguments_and_multiple_signatures() {
        $merge = function ($part1, $part2 = null) { // weird function
            if ($part2 === null)
                $part2 = $part1;
            $result = "{$part2}{$part1}";
            if (is_numeric($part1) && is_numeric($part2))
                $result = $result + 0;
            return $result;
        };

        Stream::operation('merge', [
            'Number -> Number',
            'String -> String',
            'Number -> Number -> Number',
            'String -> String -> String',
            'Number -> String -> String',
            'String -> Number -> String'
        ], $merge);

        $this->assertAll([
            [1212, Stream::of(12)->merge()->result()],
            [1214, Stream::of(12)->merge(14)->result()],
            ['case 3', Stream::of('case ')->merge(3)->result()],
            ['foofoo', Stream::of('foo')->merge()->result()],
            ['1 test', Stream::of(1)->merge(' test')->result()],
            ['all strings', Stream::of('all ')->merge('strings')->result()],
            ['mixing 123 and text', Stream::of('mixing ')->merge(123)->merge(' and ')->merge('text')->result()],
        ]);

        Stream::removeOperations('merge');
    }

    public function test_it_adds_transformations_with_then()
    {
        $add = F\curry(function($x, $y) {
            return $x + $y;
        });
        $increment = function($x) {
            return $x + 1;
        };
        Stream::operation('count', 'List -> Number');
        Stream::operation('add', 'Number -> Number -> Number', $add);

        $this->assertAll([
            // simple
            [3, Stream::of(2)->then($increment)->result()],
            // multiple
            [3, Stream::of(1)->then($increment)->then($add(1))->result()],
            // mixing then and operations
            [10, Stream::of([1, 2, 2])->count()->then($increment)->add(2)->then($add(2))->add(2)->result()]
        ]);

        Stream::removeOperations('add', 'count');
    }

    public function test_it_predicts_data_type_after_single_operation() {
        Stream::operation('work', 'Number -> String', [$this, 'fails']);
        $this->assertEquals('String', Stream::of(11)->work()->type());
        Stream::removeOperations('work');
    }

    public function test_it_predicts_data_type_after_multiple_operations() {
        Stream::operation('work', [
            'Number -> String',
            'List -> Array'
        ], [$this, 'fails']);
        Stream::operation('doIt', [
            'String -> List',
            'Array -> Function'
        ], [$this, 'fails']);

        $this->assertEquals('Function', Stream::of(11)->work()->doIt()->work()->doIt()->type());

        Stream::removeOperations('work', 'doIt');
    }

    public function test_it_accepts_any_as_return_type() {
        Stream::operation('stuff', 'Number -> Any', function($n) {
            if ($n < 10)
                return 1.2; // Number
            if ($n < 50)
                return 'Hello'; // String
            return [1, 2, 3]; // List
        });
        Stream::operation('otherStuff', 'Number|List -> String', F\toString());

        $this->assertEquals('1.2', Stream::of(1)->stuff()->otherStuff()->result());
        $this->assertEquals('[1, 2, 3]', Stream::of(100)->stuff()->otherStuff()->result());

        $s = Stream::of(33)->stuff()->otherStuff();

        $this->assertErrorThrown(function() use($s) {
            $s->result();
        }, "Stream: operation 'otherStuff' could not be called with arguments types (String); expected types are (Number) or (List)");
    }

    public function test_it_throws_exception_if_arguments_or_operation_are_invalid() {
        $increment = function($x) {
            return $x + 1;
        };
        Stream::operation('add', 'Number -> Number -> Number', [$this, 'fails']);
        Stream::operation('total', ['List -> Number', 'Array -> Number'], [$this, 'fails']);

        // if first operation is invalid
        try {
           Stream::of('foo')->add(5);
           $this->fails();
        } catch (\Exception $e) {
            $this->assertError($e, "Stream: wrong arguments (Number, String) given to operation 'add'");
        }

        try {
           Stream::of(4)->total();
           $this->fails();
        } catch (\Exception $e) {
            $this->assertError($e, "Stream: wrong arguments (Number) given to operation 'total'");
        }

        // if not enough arguments for operation
        try {
           Stream::of(5)->add();
           $this->fails();
        } catch (\Exception $e) {
            $this->assertError($e, "Stream: wrong arguments (Number) given to operation 'add'");
        }

        // if unknown operation
        try {
           Stream::of(5)->add(2)->foo('bar');
           $this->fails();
        } catch (\Exception $e) {
            $this->assertError($e, "Stream: call to unknown operation 'foo'");
        }

        // even if `then` is used, when possible
        try {
           Stream::of(5)->then($increment)->add(2)->total();
           $this->fails();
        } catch (\Exception $e) {
            $this->assertError($e, "Stream: wrong arguments (Number) given to operation 'total'");
        }

        Stream::removeOperations('add', 'total');
    }

}
