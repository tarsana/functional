# common

## Table Of Contents

- [type](https://github.com/tarsana/functional/blob/master/docs/common.md#type)

- [toString](https://github.com/tarsana/functional/blob/master/docs/common.md#toString)

- [s](https://github.com/tarsana/functional/blob/master/docs/common.md#s)

## type

```php
type(mixed $data) : string
```



Gets the type of the given argument.
```php
type(null); // 'Null'
type(true); // 'Boolean'
type(false); // 'Boolean'
type('Hello World'); // 'String'
type(1234); // 'Number'
type('123'); // 'String'
type(function($x) {return $x;}); // 'Function'
type(new \stdClass); // 'Object'
type(['name' => 'Foo', 'age' => 21]); // 'ArrayObject'
type(['Hello', 'World', 123, true]); // 'List'
type(['name' => 'Foo', 'Hello', 'Mixed']); // 'Array'
type(fopen('php://temp')); // 'Resource'
type(Error::of('Ooops !')); // 'Error'
// Anything else is 'Unknown'
```

## toString

```php
toString(mixed $something) : string
```

```
* -> String
```

Converts a variable to its string value.
```php
toString(53)); // '53'
toString(true)); // 'true'
toString(false)); // 'false'
toString(null)); // 'null'
toString('Hello World')); // 'Hello World'
toString([])); // '[]'
toString(new \stdClass)); // '[Object]'
toString(function(){})); // '[Function]'
toString(Error::of('Ooops'))); // '[Error: Ooops]'
toString(fopen('php://temp', 'r'))); // '[Resource]'
toString(['hi', 'hello', 'yo'])); // '[hi, hello, yo]'
toString([
    'object' => Stream::of(null),
    'numbers' => [1, 2, 3],
    'message'
]); // '[object => Stream(Null), numbers => [1, 2, 3], 0 => message]'
```

## s

```php
s(mixed $data) : Stream
```

```
a -> Stream(a)
```

Creates a `Stream` containing the provided data.
```php
s('! World Hello')
    ->then(split(' '))
    ->then('array_reverse')
    ->then(join(' '))
    ->get(); // 'Hello World !'
```