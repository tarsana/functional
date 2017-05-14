# Tarsana Functional

[![Build Status](https://travis-ci.org/tarsana/functional.svg?branch=master)](https://travis-ci.org/tarsana/functional)
[![Coverage Status](https://coveralls.io/repos/github/tarsana/functional/badge.svg?branch=master)](https://coveralls.io/github/tarsana/functional?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tarsana/functional/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tarsana/functional/?branch=master)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](https://github.com/tarsana/functional/blob/master/LICENSE)

Functional programming library for Tarsana

# Table of Contents

- [Introduction](#introduction)

- [Get Started](#get-started)

- [Features](#features)

- [Functions](#functions)

- [Classes](#classes)

- [Tests](#tests)

- [Contributing](#contributing)

- [Changes Log](#changes-log)


# Introduction

**What is that ?**

This is a [Functional Programming](https://en.wikipedia.org/wiki/Functional_programming) library for PHP.

**Why Functional Programming ? Isn't Object Oriented good enough ?**

Well, it dependes on your needs. FP and OOP are very different. Personally I like FP because the code is easier to write, test and maintain; even if it runs generally slower than the equivalent procedural or OOP code.

**Just Googled and found many FP libraries for PHP. Why are you writing a new one ?**

This library is inspired by [Ramda](http://ramdajs.com/) which is a FP library for Javascript. Ramda was created after [underscore](http://underscorejs.org/) and [lodash](https://lodash.com/) and it has a better Functional API then others. [This talk explains how](https://www.youtube.com/watch?v=m3svKOdZijA&app=desktop). So I wanted a library with the same philisophy as Ramda supporting old versions of PHP (from version 5.4).

# Get Started

You can install this library using [composer](https://getcomposer.org/)

```
composer require tarsana/functional
```

Then you can use it by importing the `Tarsana\Functional` namespace.

```php
use Tarsana\Functional as F;
// all functions are defined in this namespace

$incrementAll = F\map(F\plus(1));

$incrementAll([1, 2, 3]); //=> [2, 3, 4]
```

# Features

The main features of this library are:

- [Ramda](http://ramdajs.com/) like functional API with [curry()](https://github.com/tarsana/functional/blob/master/docs/functions.md#curry) and [__()](https://github.com/tarsana/functional/blob/master/docs/functions.md#__).

- **100+** Functions covered with **140+** Tests Cases containing **390+** assertions.

- All functions are **curried** out of the box.

- No dependencies !

- Supporting PHP versions since **5.4**

- Flexible [Stream](https://github.com/tarsana/functional/blob/master/docs/stream.md) class.

# Functions

Functions are grouped into modules

- [Functions](https://github.com/tarsana/functional/blob/master/docs/functions.md)

- [List](https://github.com/tarsana/functional/blob/master/docs/list.md)

- [Object](https://github.com/tarsana/functional/blob/master/docs/object.md)

- [String](https://github.com/tarsana/functional/blob/master/docs/string.md)

- [Math](https://github.com/tarsana/functional/blob/master/docs/math.md)

- [Operators](https://github.com/tarsana/functional/blob/master/docs/operators.md)

- [Common](https://github.com/tarsana/functional/blob/master/docs/common.md)

# Classes

**Why classes ? Isn't that a FUNCTIONAL library ?**

We can use classes to define Types and Containers as long as they are **immutable** and have **pure methods**. Defining a container as a class gives us a fluent API and elegant code.

The main class defined in this library is `Stream`. It's an immutable data container with lazy evaluation and type errors detection. It will allow you to write code like the following:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Tarsana\Functional as F;
use Tarsana\Functional\Stream;

// Define new Stream operations
Stream::operation('contents', 'String -> String', 'file_get_contents');

$s = Stream::of('temp.txt') // initializing the Stream with the filename
    ->contents() // Reading the content of the file using the operation we defined
    ->regReplace('/[^a-zA-Z0-9 ]/', ' ') // removing non-alphanumeric chars
    ->split(' ') // Splitting text into words
    ->filter(F\notEq('')) // removing empty words
    ->map(F\lowerCase()) // makes all words in lower case
    ->reduce(function($words, $w) {
        return F\has($w, $words)
            ? F\update($w, F\plus(1), $words)
            : F\set($w, 1, $words);
    }, []); // transform the content to an array associating each word to occurences

print_r($s->result());
```

Then if the file `temp.txt` contains:

```
We can use classes to define Types and Containers as long as they are **immutable** and have **pure methods**. Defining a container as a class gives us a fluent API and elegant code.
```

The code above will output:

```
Array
(
    [we] => 1
    [can] => 1
    [use] => 1
    [classes] => 1
    [to] => 1
    [define] => 1
    [types] => 1
    [and] => 3
    [containers] => 1
    [as] => 3
    [long] => 1
    [they] => 1
    [are] => 1
    [immutable] => 1
    [have] => 1
    [pure] => 1
    [methods] => 1
    [defining] => 1
    [a] => 3
    [container] => 1
    [class] => 1
    [gives] => 1
    [us] => 1
    [fluent] => 1
    [api] => 1
    [elegant] => 1
    [code] => 1
)
```

[Click here to learn more about Stream](https://github.com/tarsana/functional/blob/master/docs/stream.md)

There is also the `Tarsana\Functional\Error` class which is just extending the default `Exception` class and providing a static method `Error::of('msg')` to create new errors without using the `new` operator.

# Tests

All tests are under the `tests` directory. they can be run using `phpunit`.

# Contributing

Please consider reading the [Contribution Guide](https://github.com/tarsana/functional/blob/master/CONTRIBUTING.md), it will help you to understand how is the project structured and why I am including a `build.php` and `package.json` files !

# Changes Log

**Version 2.2.0**

- [compose](https://github.com/tarsana/functional/blob/master/docs/functions.md#compose) function added as requested [here](https://github.com/tarsana/functional/issues/4).

- [Contribution Guide](https://github.com/tarsana/functional/blob/master/CONTRIBUTING.md) added.

- **109** Functions, **152** Tests with **421** assertions.

**Version 2.1.0**

- Improving efficiency of the library.

- **108** Functions, **151** Tests with **420** assertions.

**Version 2.0.0**

- `cypresslab/php-curry` dependency removed.

- New modules: Object and Common.

- **108** Functions, **143** Tests with **393** assertions.

- New build script to generate docs and some unit tests.

- Stream class rewritten to support custom operations. `get()` is now called `result()` to avoid conflict with [get()](https://github.com/tarsana/functional/blob/master/docs/object.md#get).

**Version 1.1.0**

- Fix `remove` bug: made it curried.

**Version 1.0.0**

- 5 modules (Functions, Operators, String, List, Math) containing **64 functions**.
- Stream: a lazy data container
