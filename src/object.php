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
    $clone = $clone ?: curry(function($value) use(&$clone) {
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
                return array_map($clone, $value);
            case 'Error':
            case 'Stream':
            case 'Object':
                $result = clone $value;
                foreach (keys($value) as $key) {
                    $result->{$key} = $clone($result->{$key});
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
 *
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
 * @stream
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
 * @stream
 * @signature [*] -> [Number]
 * @signature {k: v} -> [k]
 * @param object|array $object
 * @return array
 */
function keys() {
    static $keys = false;
    $keys = $keys ?: curry(function($object) {
        return is_object($object)
            ? array_keys(get_object_vars($object))
            : array_keys($object);
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
 * @stream
 * @signature [a] -> [a]
 * @signature {k: v} -> [v]
 * @param object|array $object
 * @return array
 */
function values() {
    static $values = false;
    $values = $values ?: curry(function($object) {
        return is_object($object)
            ? array_values(get_object_vars($object))
            : array_values($object);
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
 * @stream
 * @signature k -> {k: v} -> Boolean
 * @param  string|int $name
 * @param  mixed $object
 * @return bool
 */
function has() {
    static $has = false;
    $has = $has ?: curry(function($name, $object){
        if (is_object($object)) return isset($object->{$name});
        if (is_array($object)) return isset($object[$name]);
        return false;
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
 * @stream
 * @signature k -> {k: v} -> Maybe(v)
 * @param  string $name
 * @param  array $object
 * @return mixed
 */
function get() {
    static $get = false;
    $get = $get ?: curry(function($name, $object){
        return is_object($object)
            ? (isset($object->{$name}) ? $object->{$name} : null)
            : (isset($object[$name]) ? $object[$name] : null);
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
 * F\getPath([2, 'foo', 1], $data); //=> null
 * ```
 *
 * @stream
 * @signature [k] -> {k: v} -> v
 * @param  array $path
 * @param  mixed $object
 * @return mixed
 */
function getPath() {
    static $getPath = false;
    $getPath = $getPath ?: curry(function($path, $object) {
        $result = $object;
        foreach ($path as &$attr) {
            $result = get($attr, $result);
        }
        return $result;
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
 * @stream
 * @signature k -> v -> {k: v} -> {k: v}
 * @param  string|int $name
 * @param  mixed $value
 * @param  mixed $object
 * @return mixed
 */
function set() {
    static $set = false;
    $set = $set ?: curry(function($name, $value, $object) {
        if (is_object($object)) {
            $object = clone_($object);
            $object->{$name} = $value;
        } else
            $object[$name] = $value;
        return $object;
    });
    return _apply($set, func_get_args());
}

/**
 * Updates the value of a key or public attribute using a callable.
 *
 * ```php
 * $person = [
 *     'name' => 'foo',
 *     'age' => 11
 * ];
 * $growUp = F\update('age', F\plus(1));
 * $growUp($person); //=> ['name' => 'foo', 'age' => 12]
 * // updating a missing attribute has no effect
 * F\update('wow', F\plus(1), $person); //=> ['name' => 'foo', 'age' => 11]
 * ```
 *
 * @stream
 * @signature k -> (v -> v) -> {k: v} -> {k: v}
 * @param  string|int $name
 * @param  callable $fn
 * @param  mixed $object
 * @return mixed
 */
function update() {
    static $update = false;
    $update = $update ?: curry(function($name, $fn, $object) {
        $value = get($name, $object);
        return (null === $value) ? $object : set($name, $fn($value), $object);
    });
    return _apply($update, func_get_args());
}

/**
 * Checks if an attribute/value of an object/array passes the given predicate.
 *
 * ```php
 * $foo = ['name' => 'foo', 'age' => 11];
 * $isAdult = F\satisfies(F\lte(18), 'age');
 * F\satisfies(F\startsWith('f'), 'name', $foo); //=> true
 * F\satisfies(F\startsWith('g'), 'name', $foo); //=> false
 * F\satisfies(F\startsWith('g'), 'friends', $foo); //=> false
 * $isAdult($foo); //=> false
 * ```
 *
 * @stream
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
 *     'age' => F\lte(15)
 * ]);
 *
 * F\filter($isValid, $persons); //=> [['name' => 'baz', 'age' => 16], ['name' => 'beta', 'age' => 25]]
 * ```
 *
 * @stream
 * @signature {String: (a -> Boolean)} -> {k : a} -> Boolean
 * @param  array $predicates
 * @param  mixed $object
 * @return bool
 */
function satisfiesAll() {
    static $satisfiesAll = false;
    $satisfiesAll = $satisfiesAll ?: curry(function($predicates, $object) {
        foreach ($predicates as $key => $predicate) {
            if (!satisfies($predicate, $key, $object))
                return false;
        }
        return true;
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
 *     'age' => F\lte(15)
 * ]);
 *
 * F\filter($isValid, $persons); //=> [['name' => 'bar', 'age' => 9], ['name' => 'baz', 'age' => 16], ['name' => 'zeta', 'age' => 33], ['name' => 'beta', 'age' => 25]]
 * ```
 *
 * @stream
 * @signature {String: (a -> Boolean)} -> {k : a} -> Boolean
 * @param  array $predicates
 * @param  mixed $object
 * @return bool
 */
function satisfiesAny() {
    static $satisfiesAny = false;
    $satisfiesAny = $satisfiesAny ?: curry(function($predicates, $object) {
        foreach ($predicates as $key => $predicate) {
            if (satisfies($predicate, $key, $object))
                return true;
        }
        return false;
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
 * @stream
 * @signature {k: v} -> [(k,v)]
 * @signature [v] -> [(Number,v)]
 * @param  array $object
 * @return array
 */
function toPairs() {
    static $toPairs = false;
    $toPairs = $toPairs ?: curry(function($object) {
        if (is_object($object))
            $object = get_object_vars($object);
        $result = [];
        foreach ($object as $key => $value) {
            $result[] = [$key, $value];
        }
        return $result;
    });
    return _apply($toPairs, func_get_args());
}
