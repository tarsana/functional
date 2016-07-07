# Stream

## Table Of Contents

- [Stream::of](https://github.com/tarsana/functional/blob/master/docs/Stream.md#Stream::of)

- [__toString](https://github.com/tarsana/functional/blob/master/docs/Stream.md#__toString)

- [get](https://github.com/tarsana/functional/blob/master/docs/Stream.md#get)

- [map](https://github.com/tarsana/functional/blob/master/docs/Stream.md#map)

- [filter](https://github.com/tarsana/functional/blob/master/docs/Stream.md#filter)

- [reduce](https://github.com/tarsana/functional/blob/master/docs/Stream.md#reduce)

- [chain](https://github.com/tarsana/functional/blob/master/docs/Stream.md#chain)

- [length](https://github.com/tarsana/functional/blob/master/docs/Stream.md#length)

- [take](https://github.com/tarsana/functional/blob/master/docs/Stream.md#take)

- [then](https://github.com/tarsana/functional/blob/master/docs/Stream.md#then)

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

## __toString

```php
__toString() : string
```

```
Stream(*) -> String
```

Returns a string representation of a Stream.
```php
$s = Stream::of(55);
echo $s; // Outputs: Stream(Number)
$s = Stream::of([1, 2, 3]);
echo $s; // Outputs: Stream(List)
$s = Stream::of(Error::of('Ooops'));
echo $s; // Outputs: Stream(Error)
```

## get

```php
get() : mixed
```

```
Stream(a) -> a
```

Executes the operations and returns the resulting data.
```php
$s = Stream::of(55)->then(plus(5));
$s->get(); // 60
$s = Stream::of([1, 2, 3])->length();
$s->get(); // 3
```

## map

```php
map(callable $fn) : Stream
```

```
Stream([a]) -> (a -> b) -> Stream([b])
```

Applies a function to items of the stream.
```php
Stream::of([1, 2, 3])->map(function($n){
   return $n * $n;
})->get() // [1, 4, 9]
```

## filter

```php
filter(callable $predicate) : Stream
```

```
Stream([a]) -> (a -> Boolean) -> Stream([a])
```

Filters items of the stream.
```php
Stream::of(['1', null, 2, 'hi'])
    ->filter('is_numeric')
    ->get() // ['1', 2]
```

## reduce

```php
reduce(callable $fn, mixed $initial) : Stream
```

```
Stream([a]) -> (* -> a -> *) -> * -> Stream(*)
```

Reduces the content of the stream.
```php
Stream::of([1, 2, 3, 4])
    ->reduce('Tarsana\\Functional\\plus', 0)
    ->get() // 10
```

## chain

```php
chain(callable $fn) : Stream
```

```
Stream([a]) -> (a -> [b]) -> Stream([b])
```

Chains a function over the content of the stream.
This is called `flatMap` in other libraries.
```php
Stream::of(['Hello you', 'How are you'])
    ->chain(split(' '))
    ->get() // ['Hello', 'you', 'How', 'are', 'you']
```

## length

```php
length() : Stream
```

```
Stream([*]) -> Number
Stream(String) -> Number
```

Returns the length of the stream.
```php
Stream::of(['Hello you', 'How are you'])
    ->length()
    ->get() // 2
Stream::of('Hello you')
    ->length()
    ->get() // 9
```

## take

```php
take(int $number) : Stream
```

```
Stream([a]) -> Number -> Stream([a])
Stream(String) -> Number -> Stream(String)
```

Takes a number of items from the stream.
```php
Stream::of([1, 2, 3, 4, 5])
    ->take(3)
    ->get() // [1, 2, 3]
Stream::of('Hello World')
    ->take(5)
    ->get() // 'Hello'
```

## then

```php
then(callable $fn) : Stream
```

```
Stream(a) -> (a -> b) -> Stream(b)
```

Applies a custom function on the content of the stream.
```php
Stream::of('Hello')
    ->then('strtoupper')
    ->get() // 'HELLO'
Stream::of('   Hello ')
    ->then('trim')
    ->get() // 'Hello'
```