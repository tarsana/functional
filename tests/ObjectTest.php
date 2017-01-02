<?php namespace Tarsana\UnitTests\Functional;

use Tarsana\Functional as F;

class ObjectTest extends \Tarsana\UnitTests\Functional\UnitTest {

	public function test_clone_() {
		$data = (object) [
		    'content' => (object) ['name' => 'foo'],
		    'other' => 'value'
		];
		$clonedData = F\clone_($data);
		$clonedData->content->name = 'bar';
		$this->assertEquals((object) ['content' => (object) ['name' => 'bar'], 'other' => 'value'], $clonedData);
		$this->assertEquals((object) ['content' => (object) ['name' => 'foo'], 'other' => 'value'], $data);
	}

	public function test_attributes() {
		$test = new AttributesTestClass;
		$this->assertEquals(['b' => 1, 'c' => null], F\attributes($test));
	}

	public function test_keys() {
		$this->assertEquals([0, 1, 2], F\keys([1, 2, 3]));
		$this->assertEquals(['name', 'age'], F\keys(['name' => 'foo', 'age' => 11]));
		$this->assertEquals(['name', 'age'], F\keys((object)['name' => 'foo', 'age' => 11]));
	}

	public function test_values() {
		$this->assertEquals([1, 2, 3], F\values([1, 2, 3]));
		$this->assertEquals(['foo', 11], F\values(['name' => 'foo', 'age' => 11]));
		$this->assertEquals(['foo', 11], F\values((object)['name' => 'foo', 'age' => 11]));
	}

	public function test_has() {
		$array = [
		    'type' => 'Array',
		    'length' => 78
		];
		$array[3] = 'three';
		$object = (object) ['name' => 'ok'];
		$hasName = F\has('name');
		$this->assertEquals(true, F\has('type', $array));
		$this->assertEquals(true, F\has(3, $array));
		$this->assertEquals(false, $hasName($array));
		$this->assertEquals(true, $hasName($object));
		$this->assertEquals(false, F\has('length', $object));
		$this->assertEquals(true, F\has('a', new HasTestClass));
		$this->assertEquals(false, F\has('b', new HasTestClass));
	}

	public function test_get() {
		$data = [
		    ['name' => 'foo', 'type' => 'test'],
		    ['name' => 'bar', 'type' => 'test'],
		    (object) ['name' => 'baz'],
		    [1, 2, 3]
		];
		$nameOf = F\get('name');
		$this->assertEquals(['name' => 'foo', 'type' => 'test'], F\get(0, $data));
		$this->assertEquals('bar', $nameOf($data[1]));
		$this->assertEquals('baz', $nameOf($data[2]));
		$this->assertEquals(null, $nameOf($data[3]));
	}

	public function test_getPath() {
		$data = [
		    ['name' => 'foo', 'type' => 'test'],
		    ['name' => 'bar', 'type' => 'test'],
		    (object) ['name' => 'baz', 'scores' => [1, 2, 3]]
		];
		$nameOfFirst = F\getPath([0, 'name']);
		$this->assertEquals('foo', $nameOfFirst($data));
		$this->assertEquals(2, F\getPath([2, 'scores', 1], $data));
		$this->assertEquals(null, F\getPath([2, 'foo', 1], $data));
	}

	public function test_set() {
		$task = ['name' => 'test', 'complete' => false];
		$done = F\set('complete', true);
		$this->assertEquals(['name' => 'test', 'complete' => true], $done($task));
		$this->assertEquals((object) ['name' => 'test', 'complete' => true], $done((object) $task));
		$this->assertEquals(['name' => 'test', 'complete' => false, 'description' => 'Some text here'], F\set('description', 'Some text here', $task));
	}

	public function test_update() {
		$person = [
		    'name' => 'foo',
		    'age' => 11
		];
		$growUp = F\update('age', F\plus(1));
		$this->assertEquals(['name' => 'foo', 'age' => 12], $growUp($person));
		// updating a missing attribute has no effect
		$this->assertEquals(['name' => 'foo', 'age' => 11], F\update('wow', F\plus(1), $person));
	}

	public function test_satisfies() {
		$foo = ['name' => 'foo', 'age' => 11];
		$isAdult = F\satisfies(F\lte(18), 'age');
		$this->assertEquals(true, F\satisfies(F\startsWith('f'), 'name', $foo));
		$this->assertEquals(false, F\satisfies(F\startsWith('g'), 'name', $foo));
		$this->assertEquals(false, F\satisfies(F\startsWith('g'), 'friends', $foo));
		$this->assertEquals(false, $isAdult($foo));
	}

	public function test_satisfiesAll() {
		$persons = [
		    ['name' => 'foo', 'age' => 11],
		    ['name' => 'bar', 'age' => 9],
		    ['name' => 'baz', 'age' => 16],
		    ['name' => 'zeta', 'age' => 33],
		    ['name' => 'beta', 'age' => 25]
		];
		$isValid = F\satisfiesAll([
		    'name' => F\startsWith('b'),
		    'age' => F\lte(15)
		]);
		$this->assertEquals([['name' => 'baz', 'age' => 16], ['name' => 'beta', 'age' => 25]], F\filter($isValid, $persons));
	}

	public function test_satisfiesAny() {
		$persons = [
		    ['name' => 'foo', 'age' => 11],
		    ['name' => 'bar', 'age' => 9],
		    ['name' => 'baz', 'age' => 16],
		    ['name' => 'zeta', 'age' => 33],
		    ['name' => 'beta', 'age' => 25]
		];
		$isValid = F\satisfiesAny([
		    'name' => F\startsWith('b'),
		    'age' => F\lte(15)
		]);
		$this->assertEquals([['name' => 'bar', 'age' => 9], ['name' => 'baz', 'age' => 16], ['name' => 'zeta', 'age' => 33], ['name' => 'beta', 'age' => 25]], F\filter($isValid, $persons));
	}

	public function test_toPairs() {
		$list = ['key' => 'value', 'number' => 53, 'foo', 'bar'];
		$this->assertEquals([['key', 'value'], ['number', 53], [0, 'foo'], [1, 'bar']], F\toPairs($list));
	}
}

class AttributesTestClass {
    private $a;
    public $b = 1;
    public $c;
    private $d;
    static $e;
}

class HasTestClass {
    public $a = 1;
    private $b = 2;
    protected $c = 3;
    public $d;
}

