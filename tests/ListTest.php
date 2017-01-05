<?php namespace Tarsana\UnitTests\Functional;

use Tarsana\Functional as F;

class ListTest extends \Tarsana\UnitTests\Functional\UnitTest {

	public function test_map() {
		$doubles = F\map(function($x) { return 2 * $x; });
		$this->assertEquals([2, 4, 6, 8], $doubles([1, 2, 3, 4]));
	}

	public function test_chain() {
		$words = F\chain(F\split(' '));
		$this->assertEquals(['Hello', 'World', 'How', 'are', 'you'], $words(['Hello World', 'How are you']));
	}

	public function test_filter() {
		$list = [1, 'aa', 3, [4, 5]];
		$numeric = F\filter('is_numeric');
		$this->assertEquals([1, 3], $numeric($list));
	}

	public function test_reduce() {
		$list = [1, 2, 3, 4];
		$sum = F\reduce('Tarsana\Functional\plus', 0);
		$this->assertEquals(10, $sum($list));
	}

	public function test_each() {
		$list = [1, 2, 3, 4];
		$s = 0;
		F\each(function($item) use(&$s){
		    $s += $item;
		}, $list);
		$this->assertEquals(10, $s);
	}

	public function test_head() {
		$this->assertEquals(1, F\head([1, 2, 3, 4]));
		$this->assertEquals('H', F\head('Hello'));
		$this->assertEquals(null, F\head([]));
		$this->assertEquals('', F\head(''));
	}

	public function test_last() {
		$this->assertEquals(4, F\last([1, 2, 3, 4]));
		$this->assertEquals('o', F\last('Hello'));
		$this->assertEquals(null, F\last([]));
		$this->assertEquals('', F\last(''));
	}

	public function test_init() {
		$this->assertEquals([1, 2, 3], F\init([1, 2, 3, 4]));
		$this->assertEquals('Hell', F\init('Hello'));
		$this->assertEquals([], F\init([7]));
		$this->assertEquals([], F\init([]));
		$this->assertEquals('', F\init(''));
	}

	public function test_tail() {
		$this->assertEquals([2, 3, 4], F\tail([1, 2, 3, 4]));
		$this->assertEquals('ello', F\tail('Hello'));
		$this->assertEquals([], F\tail([7]));
		$this->assertEquals([], F\tail([]));
		$this->assertEquals('', F\tail(''));
	}

	public function test_reverse() {
		$this->assertEquals([4, 3, 2, 1], F\reverse([1, 2, 3, 4]));
		$this->assertEquals('olleH', F\reverse('Hello'));
	}

	public function test_length() {
		$this->assertEquals(4, F\length([1, 2, 3, 4]));
		$this->assertEquals(5, F\length('Hello'));
	}

	public function test_allSatisfies() {
		$allNotNull = F\allSatisfies(F\pipe(F\eq(0), F\not()));
		$this->assertEquals(true, $allNotNull([9, 3, 2, 4]));
		$this->assertEquals(false, $allNotNull([9, 3, 0, 4]));
	}

	public function test_anySatisfies() {
		$anyNumeric = F\anySatisfies('is_numeric');
		$this->assertEquals(true, $anyNumeric(['Hello', '12', []]));
		$this->assertEquals(false, $anyNumeric(['Hello', 'Foo']));
	}

	public function test_concat() {
		$this->assertEquals([1, 2, 3, 4], F\concat([1, 2], [3, 4]));
		$this->assertEquals('Hello World', F\concat('Hello ', 'World'));
	}

	public function test_concatAll() {
		$this->assertEquals([1, 2, 3, 4, 5, 6], F\concatAll([[1, 2], [3, 4], [5, 6]]));
		$this->assertEquals('Hello World !', F\concatAll(['Hello ', 'World', ' !']));
	}

	public function test_insert() {
		$this->assertEquals([1, 2, 'x', 3, 4], F\insert(2, 'x', [1, 2, 3, 4]));
		$this->assertEquals([1, 2, 3, 'x', 4], F\insert(-1,  'x', [1, 2, 3, 4]));
		$this->assertEquals([1, 2, 3, 4, 'x'], F\insert(11, 'x', [1, 2, 3, 4]));
		$this->assertEquals(['x', 1, 2, 3, 4], F\insert(0, 'x', [1, 2, 3, 4]));
		$this->assertEquals(['x', 1, 2, 3, 4], F\insert(-11, 'x', [1, 2, 3, 4]));
		$this->assertEquals('Hello World', F\insert(32, 'd', 'Hello Worl'));
		$this->assertEquals('Hello World', F\insert(3, 'l', 'Helo World'));
		$this->assertEquals('Hello World', F\insert(-7, 'l', 'Helo World'));
		$this->assertEquals('Hello World', F\insert(0, 'H', 'ello World'));
		$this->assertEquals('Hello World', F\insert(-70, 'H', 'ello World'));
	}

	public function test_insertAll() {
		$this->assertEquals([1, 2, 'x', 'y', 3, 4], F\insertAll(2, ['x', 'y'], [1, 2, 3, 4]));
		$this->assertEquals([1, 2, 3, 'x', 'y', 4], F\insertAll(-1,  ['x', 'y'], [1, 2, 3, 4]));
		$this->assertEquals([1, 2, 3, 4, 'x', 'y'], F\insertAll(11, ['x', 'y'], [1, 2, 3, 4]));
		$this->assertEquals(['x', 'y', 1, 2, 3, 4], F\insertAll(0, ['x', 'y'], [1, 2, 3, 4]));
		$this->assertEquals(['x', 'y', 1, 2, 3, 4], F\insertAll(-11, ['x', 'y'], [1, 2, 3, 4]));
		$this->assertEquals('Hello World', F\insertAll(2, 'llo', 'He World'));
	}

	public function test_append() {
		$this->assertEquals([1, 2, 3, 5], F\append(5, [1, 2, 3]));
		$this->assertEquals('Hello World', F\append(' World', 'Hello'));
	}

	public function test_prepend() {
		$this->assertEquals([5, 1, 2, 3], F\prepend(5, [1, 2, 3]));
		$this->assertEquals('Hello World', F\prepend('Hello ', 'World'));
	}

	public function test_take() {
		$items = ['Foo', 'Bar', 'Baz'];
		$this->assertEquals(['Foo', 'Bar'], F\take(2, $items));
		$this->assertEquals([], F\take(0, $items));
		$this->assertEquals(['Foo', 'Bar', 'Baz'], F\take(7, $items));
		$this->assertEquals(['Bar', 'Baz'], F\take(-2, $items));
		$this->assertEquals('Hello', F\take(5, 'Hello World'));
		$this->assertEquals('World', F\take(-5, 'Hello World'));
	}

	public function test_takeWhile() {
		$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
		$this->assertEquals(['Foo', 'Fun'], F\takeWhile(F\startsWith('F'), $items));
		$this->assertEquals([], F\takeWhile(F\startsWith('D'), $items));
	}

	public function test_takeLastWhile() {
		$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
		$this->assertEquals(['Bar', 'Baz'], F\takeLastWhile(F\startsWith('B'), $items));
		$this->assertEquals([], F\takeLastWhile(F\startsWith('D'), $items));
	}

	public function test_takeUntil() {
		$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
		$this->assertEquals(['Foo', 'Fun', 'Dev'], F\takeUntil(F\startsWith('B'), $items));
		$this->assertEquals([], F\takeUntil(F\startsWith('F'), $items));
	}

	public function test_takeLastUntil() {
		$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
		$this->assertEquals(['Dev', 'Bar', 'Baz'], F\takeLastUntil(F\startsWith('F'), $items));
		$this->assertEquals([], F\takeLastUntil(F\startsWith('B'), $items));
	}

	public function test_remove() {
		$items = ['Foo', 'Bar', 'Baz'];
		$this->assertEquals(['Baz'], F\remove(2, $items));
		$this->assertEquals(['Foo', 'Bar'], F\remove(-1, $items));
		$this->assertEquals([], F\remove(5, $items));
		$this->assertEquals('World', F\remove(6, 'Hello World'));
		$this->assertEquals('Hello', F\remove(-6, 'Hello World'));
	}

	public function test_removeWhile() {
		$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
		$this->assertEquals(['Dev', 'Bar', 'Baz'], F\removeWhile(F\startsWith('F'), $items));
		$this->assertEquals(['Foo', 'Fun', 'Dev', 'Bar', 'Baz'], F\removeWhile(F\startsWith('D'), $items));
	}

	public function test_removeLastWhile() {
		$items = ['Foo', 'Fun', 'Bye', 'Dev', 'Bar', 'Baz'];
		$this->assertEquals(['Foo', 'Fun', 'Bye', 'Dev', 'Bar', 'Baz'], F\removeLastWhile(F\startsWith('F'), $items));
		$this->assertEquals(['Foo', 'Fun', 'Bye', 'Dev'], F\removeLastWhile(F\startsWith('B'), $items));
	}

	public function test_removeUntil() {
		$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
		$this->assertEquals(['Bar', 'Baz'], F\removeUntil(F\startsWith('B'), $items));
		$this->assertEquals(['Foo', 'Fun', 'Dev', 'Bar', 'Baz'], F\removeUntil(F\startsWith('F'), $items));
		$this->assertEquals([], F\removeUntil(F\startsWith('A'), $items));
	}

	public function test_removeLastUntil() {
		$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
		$this->assertEquals(['Foo', 'Fun', 'Dev', 'Bar', 'Baz'], F\removeLastUntil(F\startsWith('B'), $items));
		$this->assertEquals(['Foo', 'Fun'], F\removeLastUntil(F\startsWith('F'), $items));
		$this->assertEquals([], F\removeLastUntil(F\startsWith('A'), $items));
	}

	public function test_fromPairs() {
		$this->assertEquals((object) ['name' => 'Foo', 'age' => 11], F\fromPairs([['name', 'Foo'], ['age', 11]]));
	}

	public function test_slices() {
		$pairs = F\slices(2);
		$this->assertEquals([[1, 2], [3, 4], [5]], $pairs([1, 2, 3, 4, 5]));
		$this->assertEquals(['He', 'll', 'o ', 'Wo', 'rl', 'd'], $pairs("Hello World"));
		$this->assertEquals([[1, 2]], F\slices(5, [1, 2]));
		$this->assertEquals([], F\slices(3, []));
		$this->assertEquals([''], F\slices(3, ''));
	}

	public function test_contains() {
		$this->assertEquals(true, F\contains('foo', ['foo', 'bar', 'baz']));
		$this->assertEquals(false, F\contains('hi', ['foo', 'bar', 'baz']));
		$this->assertEquals(false, F\contains('hi', 'Hello World'));
		$this->assertEquals(true, F\contains('He', 'Hello World'));
	}

	public function test_findIndex() {
		$this->assertEquals(1, F\findIndex(F\startsWith('b'), ['foo', 'bar', 'baz']));
		$this->assertEquals('b', F\findIndex(F\startsWith('b'), ['a' => 'foo', 'b' => 'bar', 'c' => 'baz']));
		$this->assertEquals(null, F\findIndex(F\startsWith('c'), ['foo', 'bar', 'baz']));
	}

	public function test_findLastIndex() {
		$this->assertEquals(2, F\findLastIndex(F\startsWith('b'), ['foo', 'bar', 'baz']));
		$this->assertEquals('c', F\findLastIndex(F\startsWith('b'), ['a' => 'foo', 'b' => 'bar', 'c' => 'baz']));
		$this->assertEquals(null, F\findLastIndex(F\startsWith('c'), ['foo', 'bar', 'baz']));
	}

	public function test_find() {
		$this->assertEquals('bar', F\find(F\startsWith('b'), ['foo', 'bar', 'baz']));
		$this->assertEquals(null, F\find(F\startsWith('c'), ['foo', 'bar', 'baz']));
	}

	public function test_findLast() {
		$this->assertEquals('baz', F\findLast(F\startsWith('b'), ['foo', 'bar', 'baz']));
		$this->assertEquals(null, F\findLast(F\startsWith('c'), ['foo', 'bar', 'baz']));
	}

	public function test_indexOf() {
		$this->assertEquals(1, F\indexOf(['Hello', 'World'], [1, ['Hello', 'World'], true, ['Hello', 'World']]));
		$this->assertEquals(-1, F\indexOf(['Hello'], [1, ['Hello', 'World'], true]));
		$this->assertEquals(6, F\indexOf('World', 'Hello World'));
		$this->assertEquals(-1, F\indexOf('World !', 'Hello World'));
		$this->assertEquals('name', F\indexOf('foo', (object) ['name' => 'foo', 'age' => 11]));
	}

	public function test_lastIndexOf() {
		$this->assertEquals(3, F\lastIndexOf(['Hello', 'World'], [1, ['Hello', 'World'], true, ['Hello', 'World']]));
		$this->assertEquals(-1, F\lastIndexOf(['Hello'], [1, ['Hello', 'World'], true]));
		$this->assertEquals(6, F\lastIndexOf('World', 'Hello World'));
		$this->assertEquals(-1, F\lastIndexOf('World !', 'Hello World'));
		$this->assertEquals('name', F\lastIndexOf('foo', (object) ['name' => 'foo', 'age' => 11]));
	}

	public function test_uniqueBy() {
		$this->assertEquals([1, '2', 3], F\uniqueBy(F\eq(), [1, '2', '1', 3, '3', 2, 2]));
	}

	public function test_unique() {
		$this->assertEquals([1, '1', [1, 2], ['1', 2]], F\unique([1, '1', [1, 2], 1, ['1', 2], [1, 2]]));
	}

	public function test_groupBy() {
		$persons = [
		    ['name' => 'foo', 'age' => 11],
		    ['name' => 'bar', 'age' => 9],
		    ['name' => 'baz', 'age' => 16],
		    ['name' => 'zeta', 'age' => 33],
		    ['name' => 'beta', 'age' => 25]
		];
		$phase = function($person) {
		    $age = $person['age'];
		    if ($age < 13) return 'child';
		    if ($age < 19) return 'teenager';
		    return 'adult';
		};
		$this->assertEquals(['child' => [['name' => 'foo', 'age' => 11], ['name' => 'bar', 'age' => 9]], 'teenager' => [['name' => 'baz', 'age' => 16]], 'adult' => [['name' => 'zeta', 'age' => 33], ['name' => 'beta', 'age' => 25]]], F\groupBy($phase, $persons));
	}

	public function test_pairsFrom() {
		$this->assertEquals([[1, 'foo'], [2, 'bar'], [3, 'baz']], F\pairsFrom([1, 2, 3], ['foo', 'bar', 'baz']));
		$this->assertEquals([[1, 'foo'], [2, 'bar']], F\pairsFrom([1, 2, 3], ['foo', 'bar']));
		$this->assertEquals([[1, 'foo'], [3, 'bar']], F\pairsFrom([1, 3], ['foo', 'bar', 'baz']));
		$this->assertEquals([], F\pairsFrom([], ['foo', 'bar', 'baz']));
	}

	public function test_sort() {
		$numbers = [4, 5, 1, 3, 1, 2, 5];
		$this->assertEquals([1, 1, 2, 3, 4, 5, 5], F\sort(F\lt(), $numbers));
		$this->assertEquals([5, 5, 4, 3, 2, 1, 1], F\sort(F\gt(), $numbers));
	}
}

