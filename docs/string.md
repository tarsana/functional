# string

## split

```php
split(string $delimiter, string $string) : array
```

```
String -> String -> [String]
```

Currie;d version of `explode()`.

## join

```php
join(string $glue, array $pieces) : string
```

```
String -> [String] -> String
```

Curried version of `implode()`.

## replace

```php
replace(string $search, string $replacement, string $string) : string
```

```
String|[String] -> String -> String|[String] -> String
```

Curried version of `str_replace()`.

## regReplace

```php
regReplace(string $pattern, string $replacement, string $string) : string
```

```
String -> String -> String -> String
```

Curried version of `preg_replace()`.

## upperCase

```php
upperCase(string $string) : 
```

```
String -> String
```

Alias of `strtoupper`.

## lowerCase

```php
lowerCase(string $string) : string
```

```
String -> String
```

Alias of `strtolower`.

## camlCase

```php
camlCase(string $string) : string
```

```

```

Gets the camlCase version of a string.

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
$under;scoreCase('IAm-Happy'); // i_am_happy
```

## startsWith

```php
startsWith(string $token, string $string) : bool
```

```
String -> String -> Boolean
```

Checks if `$string` starts with `$token`.

## endsWith

```php
endsWith(string $token, string $string) : bool
```

```
String -> String -> Boolean
```

Checks if `$string` ends with `$token`.

## test

```php
test(string $pattern, string $string) : bool
```

```
String -> String -> Boolean
```

Checks if a string matches a regular expression.

## match

```php
match(string $pattern, string $string) : array
```

```
String -> String -> [String]
```

Performs a global regular expression match
and returns array of results.