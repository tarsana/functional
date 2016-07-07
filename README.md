# Tarsana Functional

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f3809eb7-6efa-498d-83b2-598d6e7eb9c6/small.png)](https://insight.sensiolabs.com/projects/f3809eb7-6efa-498d-83b2-598d6e7eb9c6)

[![Build Status](https://travis-ci.org/tarsana/functional.svg?branch=master)](https://travis-ci.org/tarsana/functional)
[![Coverage Status](https://coveralls.io/repos/github/tarsana/functional/badge.svg?branch=master)](https://coveralls.io/github/tarsana/functional?branch=master)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](http://opensource.org/licenses/MIT)

Functional programming library for Tarsana

# Table of Contents

- [Installation](#installation)

- [Real Use Case](#real-use-case)

- [Streams](#streams) **New in version 1.1.0**

- [Reference Documentation](#reference-documentation)

# Installation

Please use **composer** to install this library:

```
composer require tarsana/functional
```

# Real use case

The script I am using to generate the [Reference Documentation](#reference-documentation) files for this library is a good example of usage: [Check the code here](https://github.com/tarsana/functional/blob/master/generate-docs.php)

# Streams

A **Stream** is a container of data that allow applying serie of operations on the data in a lazy way. You start by creating the stream by giving the initial data:
```php
$s = Stream::of('Hello World'); // Stream('Hello World')
$s = Stream::of('Hello World', 'Foo', 58); // Stream(['Hello World', 'Foo', 58])
```
Once you have a Stream, you can start applying operations on it. Each call returns a new Stream **(Streams are immutable)** and all operation calls are **pure** (No side effect !). Because operations are not applying immediately but are just saved to be applied when `get()` is called !
```php
use Tarsana\Functional\Stream;
use Tarsana\Functional as F;

$s = Stream::of('temp.txt') // initializing the Stream with the filename
    ->then('file_get_contents') // Reading the content of the file
    ->then(F\regReplace('/[^a-zA-Z0-9 ]/', ' ')) // removing non-alphanumeric chars
    ->then(F\split(' ')) // Splitting each line into words and concatenating results
    ->filter(F\notEq('')) // removing empty words
    ->reduce(function($words, $w){
        if (!isset($words[$w]))
            $words[$w] = 0;
        $words[$w] ++;
        return $words;
    }, []); // transform the content to an array associating each word to occurences

```
Till now no operation was really applied, this means that the file is not yet read !

Now when we really need the result on the operations which can have side effects (Reading the file in this case). We call the `get()` method; It will run all the operations and gets the final result.

Assuming that the file `temp.txt` contains the following:

```
Once you have a Stream, you can start applying operations on it.
Each call returns a new Stream **(Streams are immutable)** and all operation calls are **pure**
(No side effect !). Because operations are not applying immediately
but are just saved to be applied when `get()` is called !

```
Doing
```php
print_r($s->get());
```
Will output
```
Array
(
    [Once] => 1
    [you] => 2
    [have] => 1
    [a] => 2
    [Stream] => 2
    [can] => 1
    [start] => 1
    [applying] => 2
    [operations] => 2
    [on] => 1
    [it] => 1
    [Each] => 1
    [call] => 1
    [returns] => 1
    [new] => 1
    [Streams] => 1
    [are] => 4
    [immutable] => 1
    [and] => 1
    [all] => 1
    [operation] => 1
    [calls] => 1
    [pure] => 1
    [No] => 1
    [side] => 1
    [effect] => 1
    [Because] => 1
    [not] => 1
    [immediately] => 1
    [but] => 1
    [just] => 1
    [saved] => 1
    [to] => 1
    [be] => 1
    [applied] => 1
    [when] => 1
    [get] => 1
    [is] => 1
    [called] => 1
)
```

[Check documentation for full list of methods available](https://github.com/tarsana/functional/blob/master/docs/Stream.md)

# Reference Documentation

## Function Modules

- [Array](https://github.com/tarsana/functional/blob/master/docs/array.md)

	- [value](https://github.com/tarsana/functional/blob/master/docs/array#value)

	- [map](https://github.com/tarsana/functional/blob/master/docs/array#map)

	- [filter](https://github.com/tarsana/functional/blob/master/docs/array#filter)

	- [reduce](https://github.com/tarsana/functional/blob/master/docs/array#reduce)

	- [each](https://github.com/tarsana/functional/blob/master/docs/array#each)

	- [head](https://github.com/tarsana/functional/blob/master/docs/array#head)

	- [last](https://github.com/tarsana/functional/blob/master/docs/array#last)

	- [init](https://github.com/tarsana/functional/blob/master/docs/array#init)

	- [tail](https://github.com/tarsana/functional/blob/master/docs/array#tail)

	- [reverse](https://github.com/tarsana/functional/blob/master/docs/array#reverse)

	- [length](https://github.com/tarsana/functional/blob/master/docs/array#length)

	- [all](https://github.com/tarsana/functional/blob/master/docs/array#all) **since 1.1.0**

	- [any](https://github.com/tarsana/functional/blob/master/docs/array#any) **since 1.1.0**

	- [concat](https://github.com/tarsana/functional/blob/master/docs/array#concat) **since 1.1.0**

	- [append](https://github.com/tarsana/functional/blob/master/docs/array#append) **since 1.1.0**

	- [take](https://github.com/tarsana/functional/blob/master/docs/array#take) **since 1.1.0**

	- [toPairs](https://github.com/tarsana/functional/blob/master/docs/array#toPairs) **since 1.1.0**

	- [chain](https://github.com/tarsana/functional/blob/master/docs/array#chain) **since 1.1.0**


- [Functions](https://github.com/tarsana/functional/blob/master/docs/functions.md)

	- [curry](https://github.com/tarsana/functional/blob/master/docs/functions#curry)

	- [__](https://github.com/tarsana/functional/blob/master/docs/functions#__)

	- [apply](https://github.com/tarsana/functional/blob/master/docs/functions#apply)

	- [pipe](https://github.com/tarsana/functional/blob/master/docs/functions#pipe)

	- [identity](https://github.com/tarsana/functional/blob/master/docs/functions#identity)


- [Math](https://github.com/tarsana/functional/blob/master/docs/math.md)

	- [plus](https://github.com/tarsana/functional/blob/master/docs/math#plus)

	- [minus](https://github.com/tarsana/functional/blob/master/docs/math#minus)

	- [negate](https://github.com/tarsana/functional/blob/master/docs/math#negate)

	- [multiply](https://github.com/tarsana/functional/blob/master/docs/math#multiply)

	- [divide](https://github.com/tarsana/functional/blob/master/docs/math#divide)

	- [modulo](https://github.com/tarsana/functional/blob/master/docs/math#modulo)

	- [sum](https://github.com/tarsana/functional/blob/master/docs/math#sum)

	- [product](https://github.com/tarsana/functional/blob/master/docs/math#product)


- [Operators](https://github.com/tarsana/functional/blob/master/docs/operators.md)

	- [and_](https://github.com/tarsana/functional/blob/master/docs/operators#and_)

	- [or_](https://github.com/tarsana/functional/blob/master/docs/operators#or_)

	- [not](https://github.com/tarsana/functional/blob/master/docs/operators#not)

	- [eq](https://github.com/tarsana/functional/blob/master/docs/operators#eq)

	- [notEq](https://github.com/tarsana/functional/blob/master/docs/operators#notEq)

	- [eqq](https://github.com/tarsana/functional/blob/master/docs/operators#eqq)

	- [notEqq](https://github.com/tarsana/functional/blob/master/docs/operators#notEqq)

	- [lt](https://github.com/tarsana/functional/blob/master/docs/operators#lt)

	- [lte](https://github.com/tarsana/functional/blob/master/docs/operators#lte)

	- [gt](https://github.com/tarsana/functional/blob/master/docs/operators#gt)

	- [gte](https://github.com/tarsana/functional/blob/master/docs/operators#gte)

	- [type](https://github.com/tarsana/functional/blob/master/docs/operators#type) **since 1.1.0**


- [String](https://github.com/tarsana/functional/blob/master/docs/string.md)

	- [split](https://github.com/tarsana/functional/blob/master/docs/string#split)

	- [join](https://github.com/tarsana/functional/blob/master/docs/string#join)

	- [replace](https://github.com/tarsana/functional/blob/master/docs/string#replace)

	- [regReplace](https://github.com/tarsana/functional/blob/master/docs/string#regReplace)

	- [upperCase](https://github.com/tarsana/functional/blob/master/docs/string#upperCase)

	- [lowerCase](https://github.com/tarsana/functional/blob/master/docs/string#lowerCase)

	- [camelCase](https://github.com/tarsana/functional/blob/master/docs/string#camelCase)

	- [snakeCase](https://github.com/tarsana/functional/blob/master/docs/string#snakeCase)

	- [startsWith](https://github.com/tarsana/functional/blob/master/docs/string#startsWith)

	- [endsWith](https://github.com/tarsana/functional/blob/master/docs/string#endsWith)

	- [test](https://github.com/tarsana/functional/blob/master/docs/string#test)

	- [match](https://github.com/tarsana/functional/blob/master/docs/string#match)

	- [toString](https://github.com/tarsana/functional/blob/master/docs/string#toString) **since 1.1.0**

## Containers

- [Stream](https://github.com/tarsana/functional/blob/master/docs/Stream.md) **since 1.1.0**

	- [map](https://github.com/tarsana/functional/blob/master/docs/Stream.md#map)

	- [filter](https://github.com/tarsana/functional/blob/master/docs/Stream.md#filter)

	- [reduce](https://github.com/tarsana/functional/blob/master/docs/Stream.md#reduce)

	- [chain](https://github.com/tarsana/functional/blob/master/docs/Stream.md#chain)

	- [length](https://github.com/tarsana/functional/blob/master/docs/Stream.md#length)

	- [take](https://github.com/tarsana/functional/blob/master/docs/Stream.md#take)

	- [then](https://github.com/tarsana/functional/blob/master/docs/Stream.md#then)


- [Error](https://github.com/tarsana/functional/blob/master/docs/Error.md) **since 1.1.0**

	- [Error::of](https://github.com/tarsana/functional/blob/master/docs/Error#Error::of)

	- [message](https://github.com/tarsana/functional/blob/master/docs/Error#message)

	- [__toString](https://github.com/tarsana/functional/blob/master/docs/Error#__toString)

# Feedbacks & Contributions

Any feedback or contribution is welcome. Enjoy !
