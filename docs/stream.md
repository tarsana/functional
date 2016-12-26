# Stream

`Tarsana\Functional\Stream` is an immutable data container with lazy evaluation and type errors detection.

## Table of Contents

- [Basic Usage](#basic-usage)

- [Type Errors Detection](#type-errors-detection)

- [Getting the type of resulting data](#getting-the-type-of-resulting-data)

- [Defined Operations](#defined-operations)

## Basic Usage

Creating the Stream some initial data of any type:

```php
$s = Stream::of('Hello World');
```

**A Stream is a container:** it contains some data

Adding operations to be applied to data:

```php
$s = $s->lowerCase()
       ->append(' !');
```

**Stream is immutable:** each time an operation is added, a new Stream instance is returned.

**Stream is lazy:** it doesn't apply operations immediately, it simply saves them

Applying the operations and getting the result:

```php
$result = $s->result();
// $result == "hello world !"
```

## Type Errors Detection

A `Tarsana\Functional\Error` is thrown when trying to call an operation with wrong argument types.

```php
Stream::of([1, 2, 3])->lowerCase();
```

Running the following code will throw an `Error` with message `"Stream: wrong arguments (List) given to operation 'lowerCase'"` Since `lowerCase` accepts a `String` and not an a `List`.

Sometimes, it is not possible to detect type errors till the execution of operations:

```php
$s = Stream::of([[1, 2], [3, 4]])
    ->head()
    ->lowerCase();
```

Since `head` can return any type, we are not able to know if it will return a `String` or not before running it. So the error will be thrown when we call `result()` with the message `"Stream: operation 'lowerCase' could not be called with arguments types (List); expected types are (String)"`.

## Getting the type of resulting data

You can get the expected type of the result at any moment even before calling `result()` using the method `type()`.

```php
Stream::of([1, 2, 3])->type(); //=> 'Number'
Stream::of('Hello World')->split(' ')->type(); //=> 'List'
Stream::of([1, 2, 3])->head()->type(); //=> 'Any'
Stream::of([[1, 2], [3, 4]])->head()->plus(1)->type(); //=> 'Number'
```

In the last example above. There is no result type; calling `result()` will throw an Error. But `type()` is considering the working senario, in which `head` should return a `Number`.

Note that the returned types are the same returned by the function [type()](https://github.com/tarsana/functional/blob/master/docs/common.md#type) plus the generic type "Any".

## Defined Operations

`Stream` has defined operations such as `map`, `head`, `plus`, ... which are all based on functions defined in `Tarsana\Functional`.

[Click here to see the full list of operations](https://github.com/tarsana/functional/blob/master/docs/stream-operations.md)

In addition to that list, you can use the `then()` method to apply custom functions to data

```php
$words = Stream::of('path/to/text/file')
    ->then('file_get_contents')
    ->then(function($text) {
        return explode(' ', $text);
    })
    ->result();
```

## Adding Custom Operations

Using `then()` to apply custom functions can be annoying if you need to use the same custom function multiple times in multiple streams and you really don't want to declare it as a global function or store it in a global variable.

To solve this problem, `Stream` has the static method `operation` which lets you define your own operations and call them like the default ones.

```php
// Adding 'read' operation which reads the content of a text file
Stream::operation('read', 'String -> String', 'file_get_contents');
// Adding 'log' operation which logs data using `var_dump`
Stream::operation('log', 'Any -> Any', function($something) {
    var_dump($something);
    return $something;
});

$wordsCount = Stream::of('path/to/text/file')
    ->read()
    ->split(' ')
    ->log()
    ->length()
    ->result();
```

The `operation()` static method has the following prototype:

```php
public static function operation($name, $signatures, $fn = null)
```

It takes 3 arguments:

- **$name**: the name of the operation
- **$signature**: The signature of the operation, this is used in type errors detection and follows the syntax `TypeArg1 -> TypeArg2 -> ... -> TypeArgN -> ReturnType`. Types can be one of `Boolean, Number, String, Resource, Function, List, Array, Object, Any`.
- **$fn**: The callable of the operation, if not given, `$name` is considered as a function and used as callable.

