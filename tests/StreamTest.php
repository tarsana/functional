<?php

use Tarsana\Functional as F;
use Tarsana\Functional\Error;
use Tarsana\Functional\Stream;

class StreamTest extends PHPUnit_Framework_TestCase {

    public function test_stream_without_operations () {
        $s = Stream::of('data');
        $this->assertEquals('data', $s->get());
    }

    public function test_stream_map () {
        $s = Stream::of([1, 2, 3, 4, 5])
            ->map(F\plus(1));
        $this->assertEquals([2, 3, 4, 5, 6], $s->get());
    }

    public function test_stream_errors () {
        $s = Stream::of(55)
            ->map(F\plus(1));
        $this->assertTrue($s->get() instanceof Error);
        $this->assertEquals('Could not apply map to Number(55)', $s->get()->message());

        $s = $s->filter(F\eq(5))->length();
        $this->assertTrue($s->get() instanceof Error);
        $this->assertEquals(
            'Could not apply map to Number(55) -> Could not apply filter to Error -> Could not apply length to Error',
            $s->get()->message()
        );
    }

    public function test_stream_filter () {
        $s = Stream::of([1, 2, 1, 4, 5])
            ->filter(F\eq(1));
        $this->assertEquals([1, 1], $s->get());
    }

    public function test_stream_length () {
        $s = Stream::of([1, 2, 1, 4, 5])->length();
        $this->assertEquals(5, $s->get());
    }

    public function test_stream_reduce () {
        $s = Stream::of([1, 2, 1, 4, 5])->reduce('Tarsana\\Functional\\plus', 0);
        $this->assertEquals(13, $s->get());
    }

    public function test_stream_take () {
        $s = Stream::of([1, 2, 1, 4, 5])->take(2);
        $this->assertEquals([1, 2], $s->get());
    }

    public function test_stream_then () {
        $s = Stream::of([1, 2, 1, 4, 5])->then('Tarsana\\Functional\\sum');
        $this->assertEquals(13, $s->get());
    }

    public function test_chain () {
        $s = Stream::of(['Hello World', 'How are you'])
            ->chain(F\split(' '));
        $this->assertEquals(['Hello', 'World', 'How', 'are', 'you'], $s->get());
    }

    public function test_stream_filter_map_length () {
        $s = Stream::of([1, 2, 1, 4, 5])
            ->filter(F\lt(2));
        $this->assertEquals([4, 5], $s->get());
        $this->assertEquals(2, $s->length()->get());
        $this->assertEquals([true, false], $s->map(F\eq(4))->get());
        $this->assertEquals(2, $s->map(F\eq(4))->length()->get());
    }

}
