# Stream
Stream is a lazy data container.
## Stream::of
```php
Stream::of(mixed $data) : Stream
```
```
a -> Stream(a)
```
Creates a new Stream with the provided $data.
```php
Stream::of(1); // Stream(1)
Stream::of(1, 'Hello'); // Stream([1, 'Hello'])
Stream::of([1, 2, 3]); // Stream([1, 2, 3])
```
## Stream::with
```php
Stream::with(mixed $data, array $operations) : Stream
```
Stream factory function for internal use.
## Stream::optimize
```php
Stream::optimize(array $operations) : array|Error
```
Re-arrange operations to have the optimal execution.
## Stream::execute
```php
Stream::execute(array $operations, mixed $data) : mixed|Error
```
Runs the operations over data and returns the result.
## Stream::canApply
```php
Stream::canApply(string $operation, string $type) : bool
```
Checks if an operation can be applied to a specific type.
## Stream::returnOf
```php
Stream::returnOf(string $operation, string $type) : bool
```
Gets the return type of an operation when applied to a specific type.
## Stream::apply
```php
Stream::apply(string $operation, mixed $args, Stream $stream) : Stream
```
Adds an operation to a stream.
## Stream::get
```php
Stream::get() : mixed
```
Executes the operations and returns the resulting data.
## Stream::map
```php
Stream::map(callable $fn) : Stream
```
Applies a function to items of the stream.
## Stream::filter
```php
Stream::filter(callable $predicate) : Stream
```
Filters items of the stream.
## Stream::reduce
```php
Stream::reduce(callable $fn, mixed $initial) : Stream
```
Reduces the content of the stream.
## Stream::chain
```php
Stream::chain(callable $fn) : Stream
```
Chains a function over the content of the stream.
## Stream::length
```php
Stream::length() : Stream
```
Returns the length of the stream.
## Stream::take
```php
Stream::take(int $number) : Stream
```
Takes a number of items from the stream.
## Stream::then
```php
Stream::then(callable $fn) : Stream
```
Applies a custom function on the content of the stream.