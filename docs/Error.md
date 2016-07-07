# Error
This class represents an error.
## Error::of
```php
Error::of(string $message, Error|null $error) : Error
```
Creates a new Error.
## Error::message
```php
Error::message() : string
```
Gets the error's message.
## Error::__toString
```php
Error::__toString() : string
```
Returns the string representation of the error.