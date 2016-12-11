<?php namespace Tarsana\Functional;
/**
 * Useful functions to handle objects (associative arrays are considered objects).
 * @file
 */

/**
 * Returns a deep copy of the given value.
 *
 * `Callable`s are not copied but returned by reference.
 * ```php
 * $data = (object) [
 *     'content' => (object) ['name' => 'foo'],
 *     'other' => 'value'
 * ];
 *
 * $clonedData = F\clone_($data);
 * $clonedData->content->name = 'bar';
 *
 * $clonedData; //=> (object) ['content' => (object) ['name' => 'bar'], 'other' => 'value']
 * $data; //=> (object) ['content' => (object) ['name' => 'foo'], 'other' => 'value']
 * ```
 *
 * @signature a -> a
 * @param  mixed $value
 * @return mixed
 */
function clone_() {
    static $clone = false;
    $clone = $clone ?: curry(function($value) {
        switch (type($value)) {
            case 'Null':
            case 'Boolean':
            case 'String':
            case 'Function':
            case 'Resource':
            case 'Number':
                return $value;
            case 'ArrayObject':
            case 'Array':
            case 'List':
                return map(clone_(), $value);
            case 'Error':
            case 'Stream':
            case 'Object':
                $result = clone $value;
                foreach (keys($value) as $key) {
                    $result->{$key} = clone_($result->{$key});
                }
                return $result;
        }
        return $value;
    });
    return _apply($clone, func_get_args());
}

/**
 * Converts an object to an associative array containing public non-static attributes.
 *
 * If `$object` is not an object, it is returned unchanged.
 * ```php
 * class AttributesTestClass {
 *     private $a;
 *     public $b = 1;
 *     public $c;
 *     private $d;
 *     static $e;
 * }
 *
 * $test = new AttributesTestClass;
 * F\attributes($test); //=> ['b' => 1, 'c' => null]
 * ```
 *
 * @signature {k: v} -> {k: v}
 * @param  object|array $object
 * @return array
 */
function attributes() {
    static $attrs = false;
    $attrs = $attrs ?: curry(function($object) {
        if (is_object($object))
            return get_object_vars($object);
        return $object;
    });
    return _apply($attrs, func_get_args());
}

/**
 * Returns a list of array's keys or object's public attributes names.
 *
 * ```php
 * F\keys([1, 2, 3]); //=> [0, 1, 2]
 * F\keys(['name' => 'foo', 'age' => 11]); //=> ['name', 'age']
 * F\keys((object)['name' => 'foo', 'age' => 11]); //=> ['name', 'age']
 * ```
 *
 * @signature [*] -> [Number]
 * @signature {k: v} -> [k]
 * @param object|array $object
 * @return array
 */
function keys() {
    static $keys = false;
    $keys = $keys ?: curry(function($object) {
        return array_keys(attributes($object));
    });
    return _apply($keys, func_get_args());
}

/**
 * Returns a list of array's values or object's public attributes values.
 *
 * ```php
 * F\values([1, 2, 3]); //=> [1, 2, 3]
 * F\values(['name' => 'foo', 'age' => 11]); //=> ['foo', 11]
 * F\values((object)['name' => 'foo', 'age' => 11]); //=> ['foo', 11]
 * ```
 *
 * @signature [a] -> [a]
 * @signature {k: v} -> [v]
 * @param object|array $object
 * @return array
 */
function values() {
    static $values = false;
    $values = $values ?: curry(function($object) {
        return array_values(attributes($object));
    });
    return _apply($values, func_get_args());
}

/**
 * Checks if the given array or object has a specific key or public attribute.
 *
 * ```php
 * class HasTestClass {
 *     public $a = 1;
 *     private $b = 2;
 *     protected $c = 3;
 *     public $d;
 * }
 * $array = [
 *     'type' => 'Array',
 *     'length' => 78
 * ];
 * $array[3] = 'three';
 * $object = (object) ['name' => 'ok'];
 *
 * $hasName = F\has('name');
 *
 * F\has('type', $array); //=> true
 * F\has(3, $array); //=> true
 * $hasName($array); //=> false
 * $hasName($object); //=> true
 * F\has('length', $object); //=> false
 * F\has('a', new HasTestClass); //=> true
 * F\has('b', new HasTestClass); //=> false
 * ```
 *
 * @signature k -> {k: v} -> Boolean
 * @param  string|int $name
 * @param  mixed $object
 * @return bool
 */
function has() {
    static $has = false;
    $has = $has ?: curry(function($name, $object){
        return contains($name, keys($object));
    });
    return _apply($has, func_get_args());
}

/**
 * Gets the value of a key from an array or the
 * value of an public attribute from an object.
 *
 * If the key/attribute is missing, `null` is returned.
 * ```php
 * $data = [
 *     ['name' => 'foo', 'type' => 'test'],
 *     ['name' => 'bar', 'type' => 'test'],
 *     (object) ['name' => 'baz'],
 *     [1, 2, 3]
 * ];
 * $nameOf = F\get('name');
 * F\get(0, $data); //=> ['name' => 'foo', 'type' => 'test']
 * $nameOf($data[1]); //=> 'bar'
 * $nameOf($data[2]); //=> 'baz'
 * $nameOf($data[3]); //=> null
 * ```
 *
 * @signature k -> {k: v} -> Maybe(v)
 * @param  string $name
 * @param  array $object
 * @return mixed
 */
function get() {
    static $get = false;
    $get = $get ?: curry(function($name, $object){
        $object = attributes($object);
        return has($name, $object)
            ? $object[$name]
            : null;
    });
    return _apply($get, func_get_args());
}

/**
 * Gets a value from an array/object using a path of keys/attributes.
 *
 * ```php
 * $data = [
 *     ['name' => 'foo', 'type' => 'test'],
 *     ['name' => 'bar', 'type' => 'test'],
 *     (object) ['name' => 'baz', 'scores' => [1, 2, 3]]
 * ];
 * $nameOfFirst = F\getPath([0, 'name']);
 * $nameOfFirst($data); //=> 'foo'
 * F\getPath([2, 'scores', 1], $data); //=> 2
 * ```
 *
 * @signature [k] -> {k: v} -> v
 * @param  array $path
 * @param  mixed $object
 * @return mixed
 */
function getPath() {
    static $getPath = false;
    $getPath = $getPath ?: curry(function($path, $object){
        return reduce(function($result, $name) {
            if ($result !== null)
                $result = get($name, $result);
            return $result;
        }, $object, $path);
    });
    return _apply($getPath, func_get_args());
}

/**
 * Returns a new array or object with the value of a key or a public attribute set
 * to a specific value.
 *
 * if the key/attribute is missing and `$object` is an `array`
 * or `stdClass`; the key/attribute is added. Otherwise `null` is returned.
 * ```php
 * $task = ['name' => 'test', 'complete' => false];
 * $done = F\set('complete', true);
 * $done($task); //=> ['name' => 'test', 'complete' => true]
 * $done((object) $task); //=> (object) ['name' => 'test', 'complete' => true]
 * F\set('description', 'Some text here', $task); //=> ['name' => 'test', 'complete' => false, 'description' => 'Some text here']
 * ```
 *
 * @signature k -> v -> {k: v} -> {k: v}
 * @param  string|int $name
 * @param  mixed $value
 * @param  mixed $object
 * @return mixed
 */
function set() {
    static $set = false;
    $set = $set ?: curry(function($name, $value, $object) {
        $object = clone_($object);
        if (is_object($object))
            $object->{$name} = $value;
        else
            $object[$name] = $value;
        return $object;
    });
    return _apply($set, func_get_args());
}

/**
 * Checks if an attribute/value of an object/array passes the given predicate.
 *
 * ```php
 * $foo = ['name' => 'foo', 'age' => 11];
 * $isAdult = F\satisfies(F\gt(F\__(), 18), 'age');
 * F\satisfies(F\startsWith('f'), 'name', $foo); //=> true
 * F\satisfies(F\startsWith('g'), 'name', $foo); //=> false
 * F\satisfies(F\startsWith('g'), 'friends', $foo); //=> false
 * $isAdult($foo); //=> false
 * ```
 *
 * @signature (a -> Boolean) -> k -> {k : a} -> Boolean
 * @param  callable $predicate
 * @param  string|int $key
 * @param  mixed $object
 * @return bool
 */
function satisfies() {
    static $satisfies = false;
    $satisfies = $satisfies ?: curry(function($predicate, $key, $object) {
        return has($key, $object) && $predicate(get($key, $object));
    });
    return _apply($satisfies, func_get_args());
}

/**
 * Checks if a list of attribute/value of an object/array passes all the given predicates.
 *
 * ```php
 * $persons = [
 *     ['name' => 'foo', 'age' => 11],
 *     ['name' => 'bar', 'age' => 9],
 *     ['name' => 'baz', 'age' => 16],
 *     ['name' => 'zeta', 'age' => 33],
 *     ['name' => 'beta', 'age' => 25]
 * ];
 *
 * $isValid = F\satisfiesAll([
 *     'name' => F\startsWith('b'),
 *     'age' => F\gt(F\__(), 15)
 * ]);
 *
 * F\filter($isValid, $persons); //=> [['name' => 'baz', 'age' => 16], ['name' => 'beta', 'age' => 25]]
 * ```
 *
 * @signature {String: (a -> Boolean)} -> {k : a} -> Boolean
 * @param  array $predicates
 * @param  mixed $object
 * @return bool
 */
function satisfiesAll() {
    static $satisfiesAll = false;
    $satisfiesAll = $satisfiesAll ?: curry(function($predicates, $object) {
        $predicates = map(function($pair) {
            return satisfies($pair[1], $pair[0]);
        }, toPairs($predicates));
        $predicates = apply(_f('all'), $predicates);
        return $predicates($object);
    });
    return _apply($satisfiesAll, func_get_args());
}

/**
 * Checks if a list of attribute/value of an object/array passes any of the given predicates.
 *
 * ```php
 * $persons = [
 *     ['name' => 'foo', 'age' => 11],
 *     ['name' => 'bar', 'age' => 9],
 *     ['name' => 'baz', 'age' => 16],
 *     ['name' => 'zeta', 'age' => 33],
 *     ['name' => 'beta', 'age' => 25]
 * ];
 *
 * $isValid = F\satisfiesAny([
 *     'name' => F\startsWith('b'),
 *     'age' => F\gt(F\__(), 15)
 * ]);
 *
 * F\filter($isValid, $persons); //=> [['name' => 'bar', 'age' => 9], ['name' => 'baz', 'age' => 16], ['name' => 'zeta', 'age' => 33], ['name' => 'beta', 'age' => 25]]
 * ```
 *
 * @signature {String: (a -> Boolean)} -> {k : a} -> Boolean
 * @param  array $predicates
 * @param  mixed $object
 * @return bool
 */
function satisfiesAny() {
    static $satisfiesAny = false;
    $satisfiesAny = $satisfiesAny ?: curry(function($predicates, $object) {
        $predicates = map(function($pair) {
            return satisfies($pair[1], $pair[0]);
        }, toPairs($predicates));
        $predicates = apply(_f('any'), $predicates);
        return $predicates($object);
    });
    return _apply($satisfiesAny, func_get_args());
}

/**
 * Converts an object or associative array to an array of [key, value] pairs.
 *
 * ```php
 * $list = ['key' => 'value', 'number' => 53, 'foo', 'bar'];
 * F\toPairs($list); //=> [['key', 'value'], ['number', 53], [0, 'foo'], [1, 'bar']]
 * ```
 *
 * @signature {k: v} -> [(k,v)]
 * @param  array $object
 * @return array
 */
function toPairs() {
    static $toPairs = false;
    $toPairs = $toPairs ?: curry(function($object) {
        return map(function($key) use($object) {
            return [$key, get($key, $object)];
        }, keys($object));
    });
    return _apply($toPairs, func_get_args());
}
