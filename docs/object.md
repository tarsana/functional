# object

Useful functions to handle objects (associative arrays are considered objects).

- [clone_](#clone_) - Returns a deep copy of the given value.

- [attributes](#attributes) - Converts an object to an associative array containing public non-static attributes.

- [keys](#keys) - Returns a list of array's keys or object's public attributes names.

- [values](#values) - Returns a list of array's values or object's public attributes values.

- [has](#has) - Checks if the given array or object has a specific key or public attribute.

- [get](#get) - Gets the value of a key from an array or the
value of an public attribute from an object.

- [getPath](#getpath) - Gets a value from an array/object using a path of keys/attributes.

- [set](#set) - Returns a new array or object with the value of a key or a public attribute set
to a specific value.

- [update](#update) - Updates the value of a key or public attribute using a callable.

- [satisfies](#satisfies) - Checks if an attribute/value of an object/array passes the given predicate.

- [satisfiesAll](#satisfiesall) - Checks if a list of attribute/value of an object/array passes all the given predicates.

- [satisfiesAny](#satisfiesany) - Checks if a list of attribute/value of an object/array passes any of the given predicates.

- [toPairs](#topairs) - Converts an object or associative array to an array of [key, value] pairs.

# clone_

```php
clone_(mixed $value) : mixed
```

```
a -> a
```

Returns a deep copy of the given value.

`Callable`s are not copied but returned by reference.
```php
$data = (object) [
    'content' => (object) ['name' => 'foo'],
    'other' => 'value'
];

$clonedData = F\clone_($data);
$clonedData->content->name = 'bar';

$clonedData; //=> (object) ['content' => (object) ['name' => 'bar'], 'other' => 'value']
$data; //=> (object) ['content' => (object) ['name' => 'foo'], 'other' => 'value']
```

# attributes

```php
attributes(object|array $object) : array
```

```
{k: v} -> {k: v}
```

Converts an object to an associative array containing public non-static attributes.

If `$object` is not an object, it is returned unchanged.

```php
class AttributesTestClass {
    private $a;
    public $b = 1;
    public $c;
    private $d;
    static $e;
}

$test = new AttributesTestClass;
F\attributes($test); //=> ['b' => 1, 'c' => null]
```

# keys

```php
keys(object|array $object) : array
```

```
[*] -> [Number]
{k: v} -> [k]
```

Returns a list of array's keys or object's public attributes names.

```php
F\keys([1, 2, 3]); //=> [0, 1, 2]
F\keys(['name' => 'foo', 'age' => 11]); //=> ['name', 'age']
F\keys((object)['name' => 'foo', 'age' => 11]); //=> ['name', 'age']
```

# values

```php
values(object|array $object) : array
```

```
[a] -> [a]
{k: v} -> [v]
```

Returns a list of array's values or object's public attributes values.

```php
F\values([1, 2, 3]); //=> [1, 2, 3]
F\values(['name' => 'foo', 'age' => 11]); //=> ['foo', 11]
F\values((object)['name' => 'foo', 'age' => 11]); //=> ['foo', 11]
```

# has

```php
has(string|int $name, mixed $object) : bool
```

```
k -> {k: v} -> Boolean
```

Checks if the given array or object has a specific key or public attribute.

```php
class HasTestClass {
    public $a = 1;
    private $b = 2;
    protected $c = 3;
    public $d;
}
$array = [
    'type' => 'Array',
    'length' => 78
];
$array[3] = 'three';
$object = (object) ['name' => 'ok'];

$hasName = F\has('name');

F\has('type', $array); //=> true
F\has(3, $array); //=> true
$hasName($array); //=> false
$hasName($object); //=> true
F\has('length', $object); //=> false
F\has('a', new HasTestClass); //=> true
F\has('b', new HasTestClass); //=> false
```

# get

```php
get(string $name, array $object) : mixed
```

```
k -> {k: v} -> Maybe(v)
```

Gets the value of a key from an array or the
value of an public attribute from an object.

If the key/attribute is missing, `null` is returned.
```php
$data = [
    ['name' => 'foo', 'type' => 'test'],
    ['name' => 'bar', 'type' => 'test'],
    (object) ['name' => 'baz'],
    [1, 2, 3]
];
$nameOf = F\get('name');
F\get(0, $data); //=> ['name' => 'foo', 'type' => 'test']
$nameOf($data[1]); //=> 'bar'
$nameOf($data[2]); //=> 'baz'
$nameOf($data[3]); //=> null
```

# getPath

```php
getPath(array $path, mixed $object) : mixed
```

```
[k] -> {k: v} -> v
```

Gets a value from an array/object using a path of keys/attributes.

```php
$data = [
    ['name' => 'foo', 'type' => 'test'],
    ['name' => 'bar', 'type' => 'test'],
    (object) ['name' => 'baz', 'scores' => [1, 2, 3]]
];
$nameOfFirst = F\getPath([0, 'name']);
$nameOfFirst($data); //=> 'foo'
F\getPath([2, 'scores', 1], $data); //=> 2
F\getPath([2, 'foo', 1], $data); //=> null
```

# set

```php
set(string|int $name, mixed $value, mixed $object) : mixed
```

```
k -> v -> {k: v} -> {k: v}
```

Returns a new array or object with the value of a key or a public attribute set
to a specific value.

if the key/attribute is missing and `$object` is an `array`
or `stdClass`; the key/attribute is added. Otherwise `null` is returned.
```php
$task = ['name' => 'test', 'complete' => false];
$done = F\set('complete', true);
$done($task); //=> ['name' => 'test', 'complete' => true]
$done((object) $task); //=> (object) ['name' => 'test', 'complete' => true]
F\set('description', 'Some text here', $task); //=> ['name' => 'test', 'complete' => false, 'description' => 'Some text here']
```

# update

```php
update(string|int $name, callable $fn, mixed $object) : mixed
```

```
k -> (v -> v) -> {k: v} -> {k: v}
```

Updates the value of a key or public attribute using a callable.

```php
$person = [
    'name' => 'foo',
    'age' => 11
];
$growUp = F\update('age', F\plus(1));
$growUp($person); //=> ['name' => 'foo', 'age' => 12]
// updating a missing attribute has no effect
F\update('wow', F\plus(1), $person); //=> ['name' => 'foo', 'age' => 11]
```

# satisfies

```php
satisfies(callable $predicate, string|int $key, mixed $object) : bool
```

```
(a -> Boolean) -> k -> {k : a} -> Boolean
```

Checks if an attribute/value of an object/array passes the given predicate.

```php
$foo = ['name' => 'foo', 'age' => 11];
$isAdult = F\satisfies(F\lte(18), 'age');
F\satisfies(F\startsWith('f'), 'name', $foo); //=> true
F\satisfies(F\startsWith('g'), 'name', $foo); //=> false
F\satisfies(F\startsWith('g'), 'friends', $foo); //=> false
$isAdult($foo); //=> false
```

# satisfiesAll

```php
satisfiesAll(array $predicates, mixed $object) : bool
```

```
{String: (a -> Boolean)} -> {k : a} -> Boolean
```

Checks if a list of attribute/value of an object/array passes all the given predicates.

```php
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

F\filter($isValid, $persons); //=> [['name' => 'baz', 'age' => 16], ['name' => 'beta', 'age' => 25]]
```

# satisfiesAny

```php
satisfiesAny(array $predicates, mixed $object) : bool
```

```
{String: (a -> Boolean)} -> {k : a} -> Boolean
```

Checks if a list of attribute/value of an object/array passes any of the given predicates.

```php
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

F\filter($isValid, $persons); //=> [['name' => 'bar', 'age' => 9], ['name' => 'baz', 'age' => 16], ['name' => 'zeta', 'age' => 33], ['name' => 'beta', 'age' => 25]]
```

# toPairs

```php
toPairs(array $object) : array
```

```
{k: v} -> [(k,v)]
[v] -> [(Number,v)]
```

Converts an object or associative array to an array of [key, value] pairs.

```php
$list = ['key' => 'value', 'number' => 53, 'foo', 'bar'];
F\toPairs($list); //=> [['key', 'value'], ['number', 53], [0, 'foo'], [1, 'bar']]
```

