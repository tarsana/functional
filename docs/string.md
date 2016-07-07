# string

## Table Of Contents

- [split](https://github.com/tarsana/functional/blob/master/docs/string.md#split)

- [join](https://github.com/tarsana/functional/blob/master/docs/string.md#join)

- [replace](https://github.com/tarsana/functional/blob/master/docs/string.md#replace)

- [regReplace](https://github.com/tarsana/functional/blob/master/docs/string.md#regReplace)

- [upperCase](https://github.com/tarsana/functional/blob/master/docs/string.md#upperCase)

- [lowerCase](https://github.com/tarsana/functional/blob/master/docs/string.md#lowerCase)

- [camelCase](https://github.com/tarsana/functional/blob/master/docs/string.md#camelCase)

- [snakeCase](https://github.com/tarsana/functional/blob/master/docs/string.md#snakeCase)

- [startsWith](https://github.com/tarsana/functional/blob/master/docs/string.md#startsWith)

- [endsWith](https://github.com/tarsana/functional/blob/master/docs/string.md#endsWith)

- [test](https://github.com/tarsana/functional/blob/master/docs/string.md#test)

- [match](https://github.com/tarsana/functional/blob/master/docs/string.md#match)

- [toString](https://github.com/tarsana/functional/blob/master/docs/string.md#toString)

## split

```php
split(string $delimiter, string $string) : array
```

```
String -> String -> [String]
```

Curried version of `explode()`.
```php
$words = split(' ');
$words('Hello World'); // ['Hello', 'World']
```

## join

```php
join(string $glue, array $pieces) : string
```

```
String -> [String] -> String
```

Curried version of `implode()`.
```php
$sentence = join(' ');
$sentence(['Hello', 'World']); // 'Hello World'
```

## replace

```php
replace(string $search, string $replacement, string $string) : string
```

```
String|[String] -> String -> String|[String] -> String
```

Curried version of `str_replace()`.
```php
$string = 'a b c d e f';
$noSpace = replace(' ', '');
$noSpace($string); // 'abcdef'
replace(['a', 'b', ' '], '', $string) // 'cdef'
replace(['a', 'e', ' '], ['x', 'y', ''], $string); // 'xbcdyf'
```

## regReplace

```php
regReplace(string $pattern, string $replacement, string $string) : string
```

```
String -> String -> String -> String
```

Curried version of `preg_replace()`.
```php
$string = 'A12;b_{F}|d';
$aplha = regReplace('/[^a-z]+/i', '');
$alpha($string); // 'AbFd'
```

## upperCase

```php
upperCase(string $string) : string
```

```
String -> String
```

Alias of `strtoupper`.
```php
upperCase('hello') // 'HELLO'
```

## lowerCase

```php
lowerCase(string $string) : string
```

```
String -> String
```

Alias of `strtolower`.
```php
lowerCase('HELLO') // 'hello'
```

## camelCase

```php
camelCase(string $string) : string
```

```
String -> String
```

Gets the camlCase version of a string.
```php
camelCase('Yes, we can! 123') // 'yesWeCan123'
```

## snakeCase

```php
snakeCase(string $delimiter, string $string) : string
```

```
String -> String -> String
```

Gets the snake-case of the string using `$delimiter` as separator.
```
$underscoreCase = snakeCase('_');
$underscoreCase('IAm-Happy'); // i_am_happy
```

## startsWith

```php
startsWith(string $token, string $string) : bool
```

```
String -> String -> Boolean
```

Checks if `$string` starts with `$token`.
```php
$http = startsWith('http://');
$http('http://gitbub.com'); // true
$http('gitbub.com'); // false
```

## endsWith

```php
endsWith(string $token, string $string) : bool
```

```
String -> String -> Boolean
```

Checks if `$string` ends with `$token`.
```php
$dotCom = endsWith('.com');
$dotCom('http://gitbub.com'); // true
$dotCom('php.net'); // false
```

## test

```php
test(string $pattern, string $string) : bool
```

```
String -> String -> Boolean
```

Checks if a string matches a regular expression.
```php
$numeric = test('/^[0-9.]+$/');
$numeric('123.43'); // true
$numeric('12a3.43'); // false
```

## match

```php
match(string $pattern, string $string) : array
```

```
String -> String -> [String]
```

Performs a global regular expression match
and returns array of results.
```php
$numbers = match('/[0-9.]+/');
$numbers('Hello World'); // []
$numbers('12 is 4 times 3'); // ['12', '4', '3']
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