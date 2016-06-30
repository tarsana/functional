# string

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
replace(['a', 'b', ' '], '', $string) // 'bcdef'
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

## camlCase

```php
camlCase(string $string) : string
```

```
String -> String
```

Gets the camlCase version of a string.
```php
camlCase('Yes, we can! 123') // 'yesWeCan123'
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