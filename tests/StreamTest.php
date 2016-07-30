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
        $s = Stream::of([1, 2, 1, 4, 5])->reduce(F\f('plus'), 0);
        $this->assertEquals(13, $s->get());
    }

    public function test_stream_take () {
        $s = Stream::of([1, 2, 1, 4, 5])->take(2);
        $this->assertEquals([1, 2], $s->get());
    }

    public function test_stream_then () {
        $s = Stream::of([1, 2, 1, 4, 5])->then(F\f('sum'));
        $this->assertEquals(13, $s->get());
    }

    public function test_stream_then_multiple_params () {
        $s = Stream::of('ello ')->then(
            F\prepend('H'),
            F\append('World')
        );
        $this->assertEquals('Hello World', $s->get());
    }

    public function test_chain () {
        $s = Stream::of(['Hello World', 'How are you'])
            ->chain(F\split(' '));
        $this->assertEquals(['Hello', 'World', 'How', 'are', 'you'], $s->get());
    }

    public function test_call_method_not_found () {
        $s = Stream::of(new ForStreamCallTest)
            ->call('ooh')
            ->get();
        $this->assertTrue($s instanceof Error);
    }

    public function test_call_method_not_public () {
        $s = Stream::of(new ForStreamCallTest)
            ->call('reset')
            ->get();
        $this->assertTrue($s instanceof Error);

        $s = Stream::of(new ForStreamCallTest)
            ->call('clear')
            ->get();
        $this->assertTrue($s instanceof Error);
    }

    public function test_call () {
        $s = Stream::of(new ForStreamCallTest)
            ->call('init', 5)
            ->call('add', 1)
            ->call('addTwo', 1, 1)
            ->call('mult', 2)
            ->call('value');
        $this->assertEquals(16, $s->get());
    }

    public function test_run_method_not_found () {
        $s = Stream::of(new ForStreamRunTest)
            ->run('ooh')
            ->get();
        $this->assertTrue($s instanceof Error);
    }

    public function test_run_method_not_public () {
        $s = Stream::of(new ForStreamRunTest)
            ->run('reset')
            ->get();
        $this->assertTrue($s instanceof Error);

        $s = Stream::of(new ForStreamRunTest)
            ->run('clear')
            ->get();
        $this->assertTrue($s instanceof Error);
    }

    public function test_run () {
        $s = Stream::of(new ForStreamRunTest)
            ->run('init', 5)
            ->run('add', 1)
            ->run('addTwo', 1, 1)
            ->run('mult', 2);
        $this->assertEquals(16, $s->get()->value());
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

class ForStreamCallTest {
    protected $value;
    public function init($value) {$this->value = $value; return $this;}
    public function add($value) {$this->value += $value; return $this;}
    public function addTwo($a, $b) {$this->value += $a + $b; return $this;}
    public function mult($value) { $this->value *= $value; return $this;}
    public function value() {return $this->value;}
    protected function reset() {$this->value = 0; return $this;}
    private function clear() {$this->value = null; return $this;}
}

class ForStreamRunTest {
    protected $value;
    public function init($value) {$this->value = $value; }
    public function add($value) {$this->value += $value; }
    public function addTwo($a, $b) {$this->value += $a + $b;}
    public function mult($value) { $this->value *= $value;}
    public function value() {return $this->value;}
    protected function reset() {$this->value = 0;}
    private function clear() {$this->value = null;}
}
