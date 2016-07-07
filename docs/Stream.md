# Stream
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
## get
```php
get() : mixed
```
Executes the operations and returns the resulting data.
## map
```php
map(callable $fn) : Stream
```
Applies a function to items of the stream.
## filter
```php
filter(callable $predicate) : Stream
```
Filters items of the stream.
## reduce
```php
reduce(callable $fn, mixed $initial) : Stream
```
Reduces the content of the stream.
## chain
```php
chain(callable $fn) : Stream
```
Chains a function over the content of the stream.
## length
```php
length() : Stream
```
Returns the length of the stream.
## take
```php
take(int $number) : Stream
```
Takes a number of items from the stream.
## then
```php
then(callable $fn) : Stream
```
Applies a custom function on the content of the stream.