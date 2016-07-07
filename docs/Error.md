# Error
## Error::of
```php
Error::of(string $message, Error|null $error) : Error
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
Gets the error's message.
```php
$err = Error::of('Ooops !');
$err->message(); // 'Ooops !'
```
## __toString
```php
__toString() : string
```
Returns the string representation of the error.
```php
$err = Error::of('Ooops !');
echo $err; // Outputs: [Error: Ooops !]
```