# Contribution Guide

Thank you for thinking about contributing to this project. This document will help you to understand the structure and build system of this library.

## Table of Contents

- [Files Structure](#files-structure)

- [The Build System](#the-build-system)

    - [Generating Docs From Comments](#generating-docs-from-comments)

    - [Generating Tests From Comments](#generating-tests-from-comments)

- [Writting Additional Docs or Tests](#writting-additional-docs-or-tests)

- [Building and Running Tests](#building-and-running-tests)

## Files Structure

```
docs/            # documentation
performance/     # simple benchmark
src/
    Classes/     # all classes are here
    Internal/    # internal (private) functions
    module1.php  # functions of module 1
    module2.php  # functions of module 2
tests/           # unit tests
build.php        # the build script
composer.json
package.json
phpunit.xml
```

**Public functions** are defined under the namespace `Tarsana\Functional` and seperated in different modules inside `src`. They are documented and have unit tests.

**Private functions** are defined under the same namespace and stored inside `src/Internal`. All private functions and their file names starts with a `_` by convention, they have tests but are not documented (to keep them private).

**Classes** are stored inside `src/Classes` which is mapped to the namespace `Tarsana\Functional` using composer:

```json
// composer.json
"autoload": {
    "psr-4": {
        "Tarsana\\Functional\\": "src/Classes/"
    }
}
```

Since this is a *Functional Programming* library; classes are only used as containers exposing useful methods, they have no mutated state and all their logic is done using *pure private functions*. For example, the logic of the `Stream` class is defined inside the file `src/Internal/_stream.php` as private functions.

**Tests** of all functions are generated from their comments during the build system explained below. Additional tests for classes or functions can be added inside the directories `tests/Classes` and `tests/Additional` which are not overridden by the build system.

The directory `tests/Classes` is mapped to the namespace `Tarsana\UnitTests\Functional` using composer:

```json
// composer.json
"autoload": {
    "psr-4": {
        "Tarsana\\UnitTests\\Functional\\": "tests/"
    }
}
```

**Documentation** of all functions is generated from comments like tests.

## The Build System

A good library should have tests and documentation; but having to write code, test and docs in three different places makes the workflow slow and the maintenance hard. That's why I decided to write all in the source code, taking adventage of comments, then generate the corresponding tests and docs using the `build.php` (which is written using this same library by the way).

For example, this source code from `src/list.php`

```php
/**
 * Curried version of `array_map`.
 *
 * ```php
 * $doubles = F\map(function($x) { return 2 * $x; });
 * $doubles([1, 2, 3, 4]); //=> [2, 4, 6, 8]
 * ```
 *
 * @stream
 * @signature (a -> b) -> [a] -> [b]
 * @signature (a -> b) -> {k: a} -> {k: b}
 * @param  callable $fn
 * @param  array $list
 * @return array
 */
function map() {
    static $map = false;
    $map = $map ?: curry('array_map');
    return _apply($map, func_get_args());
}
```

Generates this test inside `tests/ListTest.php`

```php
public function test_map() {
    $doubles = F\map(function($x) { return 2 * $x; });
    $this->assertEquals([2, 4, 6, 8], $doubles([1, 2, 3, 4]));
}
```

And this part of `docs/list.md`:

---
# map

```php
map(callable $fn, array $list) : array
```

```
(a -> b) -> [a] -> [b]
(a -> b) -> {k: a} -> {k: b}
```

Curried version of `array_map`.

```php
$doubles = F\map(function($x) { return 2 * $x; });
$doubles([1, 2, 3, 4]); //=> [2, 4, 6, 8]
```
---

### Generating Docs From Comments

This following code:

```php
/**
 * description_here.
 *
 * ```php
 * // code_sample_here
 * ```
 *
 * @signature signature_here
 * @param  type1 $arg1
 * @param  type2 $arg2
 * @return type3
 */
function foo() {
    // ...
}
```

Will generate the following documentation:

---
# foo
```php
foo(type1 $arg1, type2 $arg2) : type3
```

```
signature_here
```

description_here.

```php
// code_sample_here
```
---

**Note 1**: if the file name starts with `_` (contains private functions) then no docs are generated for it.

**Note 2**: You can add multiple signatures (see the `map` example before).

**Note 3**: You can tell the build system to ignore a function by adding `@ignore` like the following:

```php
/**
 * ...
 * @ignore
 */
```

### Generating Tests From Comments

Tests are generated from the code sample inside the comment.

**Asserting Equality**

```php
/**
 * ...
 * ```php
 * $nums = [1, 2, 3];
 * F\head($nums); //=> 1
 * F\map(function($n) {
 *     return $n * 2;
 * }, $nums); //=> [2, 4, 6]
 * F\map(function($n) {
 *     return array_fill(0, $n, 1);
 * }, $nums); //=> [
 *     [1],
 *     [1, 1],
 *     [1, 1, 1]
 * ]
 * ```
 * ...
 */
function foo() {}
```

Generates

```php
public function test_foo() {
    $nums = [1, 2, 3];
    $this->assertEquals(1, F\head($nums));
    $this->assertEquals([2, 4, 6], F\map(function($n) {
        return $n * 2;
    }, $nums));
    $this->assertEquals([
        [1],
        [1, 1],
        [1, 1, 1]
    ], F\map(function($n) {
       return array_fill(0, $n, 1);
    }, $nums));
}
```

Note that when `; //=> ` is found inside a line of code; this line is converted to an equality assertion. The build system is smart enough to recognize multiline parts of the assertion but the `; //=> ` should not be at the begining or the end of a line !

For example this will not work as expected:

```
someVeryLongFunctionCall($aLotOfArgumentsHere);
//=> "Awesome result !"
```

But this will

```
someVeryLongFunctionCall(
    $aLotOfArgumentsHere); //=> "Awesome result !"
```

**Asserting Exceptions**

When there is an exception, an instance of `Tarsana\Functional\Error` is thrown.

```php
/**
 * ...
 * ```php
 * $n = F\divide(5, 0); // throws "Can't divide by 0"
 * ```
 * ...
 */
function divide($a, $b) {
    if ($b == 0)
        throw new Error("Can't divide by 0");
    return $a / $b;
}
```

Generates

```php
public function test_divide() {
    $this->assertErrorThrown(function() {
        $n = F\divide(5, 0);
    },
    "Can't divide by 0");
}
```

The `assertErrorThrown` is defined in the class `tests/UnitTest.php` which is the parent of all test classes.

Note that `; // throws ` is used the same way as `; //=> ` for equality assertions.

**Ignoring a function**

You can tell the build system to ignore a function by adding `@ignore` like the following:
```php
/**
 * ...
 * @ignore
 */
```

## Writting Additional Docs or Tests

- You are free to write additional docs inside the `docs` directory; just make sure you don't edit a document which is overriden by the build system !

- Additional tests for functions and classes can be added inside the `tests/Additional` and `tests/Classes` directory respectively.

## Building and Running Tests

The build script uses [dox](https://github.com/tj/dox) to parse the comments in the code. So make sure you have [NodeJS](https://nodejs.org) then:

```
composer install # to install phpunit
npm install      # to install dox
npm run build    # to run the build system
npm test         # to run tests
```

Happy coding !
