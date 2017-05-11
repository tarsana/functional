# common

Generic common functions.

- [type](#type) - Gets the type of the given argument.

- [is](#is) - Checks if a variable has a specific type.

- [toString](#tostring) - Converts a variable to its string value.

- [s](#s) - Creates a `Stream` containing the provided data.

# type

```php
type(mixed $data) : string
```

```
* -> String
```

Gets the type of the given argument.

```php
F\type(null); //=> 'Null'
F\type(true); //=> 'Boolean'
F\type(false); //=> 'Boolean'
F\type('Hello World'); //=> 'String'
F\type(1234); //=> 'Number'
F\type('123'); //=> 'String'
F\type(function($x) {return $x;}); //=> 'Function'
F\type(new \stdClass); //=> 'Object'
F\type(['name' => 'Foo', 'age' => 21]); //=> 'Array'
F\type(['Hello', 'World', 123, true]); //=> 'List'
F\type(['name' => 'Foo', 'Hello', 'Mixed']); //=> 'Array'
F\type(fopen('php://temp', 'w')); //=> 'Resource'
F\type(F\Error::of('Ooops !')); //=> 'Error'
F\type(F\Stream::of('Hello')); //=> 'Stream'
// Anything else is 'Unknown'
```

# is

```php
is(string $type, mixed $data) : boolean
```

```
String -> * -> Boolean
```

Checks if a variable has a specific type.

```php
$isNumber = F\is('Number');
$isNumber(5); //=> true
$isNumber('5'); //=> false
F\is('Any', '5'); //=> true
F\is('Any', [1, 2, 3]); //=> true
```

# toString

```php
toString(mixed $something) : string
```

```
* -> String
```

Converts a variable to its string value.

```php
F\toString(53); //=> '53'
F\toString(true); //=> 'true'
F\toString(false); //=> 'false'
F\toString(null); //=> 'null'
F\toString('Hello World'); //=> '"Hello World"'
F\toString([]); //=> '[]'
F\toString(new \stdClass); //=> '{}'
F\toString(function(){}); //=> '[Function]'
F\toString(F\Error::of('Ooops')); //=> '[Error: Ooops]'
F\toString(F\Stream::of('Hello')); //=> '[Stream of String]'
F\toString(fopen('php://temp', 'r')); //=> '[Resource]'
F\toString(['hi', 'hello', 'yo']); //=> '["hi", "hello", "yo"]'
F\toString([
    'object' => null,
    'numbers' => [1, 2, 3],
    'message'
]); //=> '{object: null, numbers: [1, 2, 3], 0: "message"}'
```

# s

```php
s(mixed $data) : Stream
```

```
a -> Stream(a)
```

Creates a `Stream` containing the provided data.

```php
$s = F\s('! World Hello')
    ->split(' ')
    ->reverse()
    ->join(' ');
$s->result(); //=> 'Hello World !'
```

