# Error

## Table Of Contents

- [Error::of](https://github.com/tarsana/functional/blob/master/docs/Error#Error::of)

- [message](https://github.com/tarsana/functional/blob/master/docs/Error#message)

- [__toString](https://github.com/tarsana/functional/blob/master/docs/Error#__toString)

This class represents an error.

## Error::of

```php
Error::of(string $message, Error|null $error) : Error
```

```
String -> Error
(String, Error) -> Error
```

Creates a new Error.
```php
$err = Error::of('Ooops !'); // [Error: Ooops !]
$err2 = Error::of('Second error', $err); // [Error: Second error -> Ooops !]
```

## message

```php
message() : string
```

```
Error -> String
```

Gets the error's message.
```php
$err = Error::of('Ooops !');
$err->message(); // 'Ooops !'
```

## __toString

```php
__toString() : string
```

```
Error -> String
```

Returns the string representation of the error.
```php
$err = Error::of('Ooops !');
echo $err; // Outputs: [Error: Ooops !]
```