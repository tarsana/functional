# string

This file contains some useful String functions.

- [split](#split) - Curried version of `explode`.

- [join](#join) - Curried version of `implode`.

- [replace](#replace) - Curried version of `str_replace`.

- [regReplace](#regreplace) - Curried version of `preg_replace`.

- [upperCase](#uppercase) - Alias of `strtoupper`.

- [lowerCase](#lowercase) - Alias of `strtolower`.

- [camelCase](#camelcase) - Gets the camlCase version of a string.

- [snakeCase](#snakecase) - Gets the snake-case of the string using `$delimiter` as separator.

- [startsWith](#startswith) - Checks if `$string` starts with `$token`.

- [endsWith](#endswith) - Checks if `$string` ends with `$token`.

- [test](#test) - Checks if a string matches a regular expression.

- [match](#match) - Performs a global regular expression match
and returns array of results.

- [occurences](#occurences) - Curried version of `substr_count` with changed order of parameters,

- [chunks](#chunks) - Splits a string into chunks without spliting any group surrounded with some specified characters.

# split

```php
split(string $delimiter, string $string) : array
```

```
String -> String -> [String]
```

Curried version of `explode`.

```php
$words = F\split(' ');
$words('Hello World'); //=> ['Hello', 'World']
```

# join

```php
join(string $glue, array $pieces) : string
```

```
String -> [String] -> String
```

Curried version of `implode`.

```php
$sentence = F\join(' ');
$sentence(['Hello', 'World']); //=> 'Hello World'
```

# replace

```php
replace(string $search, string $replacement, string $string) : string
```

```
String|[String] -> String|[String] -> String -> String
```

Curried version of `str_replace`.

```php
$string = 'a b c d e f';
$noSpace = F\replace(' ', '');
$noSpace($string); //=> 'abcdef'
F\replace(['a', 'b', ' '], '', $string); //=> 'cdef'
F\replace(['a', 'e', ' '], ['x', 'y', ''], $string); //=> 'xbcdyf'
```

# regReplace

```php
regReplace(string $pattern, string $replacement, string $string) : string
```

```
String -> String -> String -> String
```

Curried version of `preg_replace`.

```php
$string = 'A12;b_{F}|d';
$alpha = F\regReplace('/[^a-z]+/i', '');
$alpha($string); //=> 'AbFd'
```

# upperCase

```php
upperCase(string $string) : string
```

```
String -> String
```

Alias of `strtoupper`.

```php
F\upperCase('hello'); //=> 'HELLO'
```

# lowerCase

```php
lowerCase(string $string) : string
```

```
String -> String
```

Alias of `strtolower`.

```php
F\lowerCase('HeLLO'); //=> 'hello'
```

# camelCase

```php
camelCase(string $string) : string
```

```
String -> String
```

Gets the camlCase version of a string.

```php
F\camelCase('Yes, we can! 123'); //=> 'yesWeCan123'
```

# snakeCase

```php
snakeCase(string $delimiter, string $string) : string
```

```
String -> String -> String
```

Gets the snake-case of the string using `$delimiter` as separator.

```php
$underscoreCase = F\snakeCase('_');
$underscoreCase('IAm-Happy'); //=> 'i_am_happy'
```

# startsWith

```php
startsWith(string $token, string $string) : bool
```

```
String -> String -> Boolean
```

Checks if `$string` starts with `$token`.

```php
$http = F\startsWith('http://');
$http('http://gitbub.com'); //=> true
$http('gitbub.com'); //=> false
```

# endsWith

```php
endsWith(string $token, string $string) : bool
```

```
String -> String -> Boolean
```

Checks if `$string` ends with `$token`.

```php
$dotCom = F\endsWith('.com');
$dotCom('http://gitbub.com'); //=> true
$dotCom('php.net'); //=> false
```

# test

```php
test(string $pattern, string $string) : bool
```

```
String -> String -> Boolean
```

Checks if a string matches a regular expression.

```php
$numeric = F\test('/^[0-9.]+$/');
$numeric('123.43'); //=> true
$numeric('12a3.43'); //=> false
```

# match

```php
match(string $pattern, string $string) : array
```

```
String -> String -> [String]
```

Performs a global regular expression match
and returns array of results.

```php
$numbers = F\match('/[0-9.]+/');
$numbers('Hello World'); //=> []
$numbers('12 is 4 times 3'); //=> ['12', '4', '3']
```

# occurences

```php
occurences(string $token, string $text) : int
```

```
String -> String -> Number
```

Curried version of `substr_count` with changed order of parameters,

```php
$spaces = F\occurences(' ');
$spaces('Hello'); //=> 0
$spaces('12 is 4 times 3'); //=> 4
```

# chunks

```php
chunks(string $surrounders, string $separator, sring $text) : array
```

```
String -> String -> String -> [String]
```

Splits a string into chunks without spliting any group surrounded with some specified characters.

`$surrounders` is a string where each pair of characters specifies
the starting and ending characters of a group that should not be split.

**Note that this function assumes that the given `$text` is well formatted**

```php
$names = F\chunks('()""', ' ');
$names('Foo "Bar Baz" (Some other name)'); //=> ['Foo', '"Bar Baz"', '(Some other name)']

$groups = F\chunks('(){}', '->');
$groups('1->2->(3->4->5)->{6->(7->8)}->9'); //=> ['1', '2', '(3->4->5)', '{6->(7->8)}', '9']
```

