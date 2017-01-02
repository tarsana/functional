# Stream Operations

## operators

- [and_](https://github.com/tarsana/functional/blob/master/docs/operators.md#and_) - Returns `$a && $b`.

- [or_](https://github.com/tarsana/functional/blob/master/docs/operators.md#or_) - Returns `$a || $b`.

- [not](https://github.com/tarsana/functional/blob/master/docs/operators.md#not) - Returns `!$x`.

- [eq](https://github.com/tarsana/functional/blob/master/docs/operators.md#eq) - Returns `$x == $y`.

- [notEq](https://github.com/tarsana/functional/blob/master/docs/operators.md#notEq) - Returns `$x != $y`.

- [eqq](https://github.com/tarsana/functional/blob/master/docs/operators.md#eqq) - Returns `$x === $y`.

- [notEqq](https://github.com/tarsana/functional/blob/master/docs/operators.md#notEqq) - Returns `$x !== $y`.

- [equals](https://github.com/tarsana/functional/blob/master/docs/operators.md#equals) - Returns `true` if the two elements have the same type and are deeply equivalent.

- [equalBy](https://github.com/tarsana/functional/blob/master/docs/operators.md#equalBy) - Returns `true` if the results of applying `$fn` to `$a` and `$b` are deeply equal.

- [lt](https://github.com/tarsana/functional/blob/master/docs/operators.md#lt) - Returns `$a < $b`.

- [lte](https://github.com/tarsana/functional/blob/master/docs/operators.md#lte) - Returns `$a <= $b`.

- [gt](https://github.com/tarsana/functional/blob/master/docs/operators.md#gt) - Returns `$a > $b`.

- [gte](https://github.com/tarsana/functional/blob/master/docs/operators.md#gte) - Returns `$a >= $b`.


## common

- [is](https://github.com/tarsana/functional/blob/master/docs/common.md#is) - Checks if a variable has a specific type.

- [toString](https://github.com/tarsana/functional/blob/master/docs/common.md#toString) - Converts a variable to its string value.


## object

- [attributes](https://github.com/tarsana/functional/blob/master/docs/object.md#attributes) - Converts an object to an associative array containing public non-static attributes.

- [keys](https://github.com/tarsana/functional/blob/master/docs/object.md#keys) - Returns a list of array's keys or object's public attributes names.

- [values](https://github.com/tarsana/functional/blob/master/docs/object.md#values) - Returns a list of array's values or object's public attributes values.

- [has](https://github.com/tarsana/functional/blob/master/docs/object.md#has) - Checks if the given array or object has a specific key or public attribute.

- [get](https://github.com/tarsana/functional/blob/master/docs/object.md#get) - Gets the value of a key from an array or the
value of an public attribute from an object.

- [getPath](https://github.com/tarsana/functional/blob/master/docs/object.md#getPath) - Gets a value from an array/object using a path of keys/attributes.

- [set](https://github.com/tarsana/functional/blob/master/docs/object.md#set) - Returns a new array or object with the value of a key or a public attribute set
to a specific value.

- [update](https://github.com/tarsana/functional/blob/master/docs/object.md#update) - Updates the value of a key or public attribute using a callable.

- [satisfies](https://github.com/tarsana/functional/blob/master/docs/object.md#satisfies) - Checks if an attribute/value of an object/array passes the given predicate.

- [satisfiesAll](https://github.com/tarsana/functional/blob/master/docs/object.md#satisfiesAll) - Checks if a list of attribute/value of an object/array passes all the given predicates.

- [satisfiesAny](https://github.com/tarsana/functional/blob/master/docs/object.md#satisfiesAny) - Checks if a list of attribute/value of an object/array passes any of the given predicates.

- [toPairs](https://github.com/tarsana/functional/blob/master/docs/object.md#toPairs) - Converts an object or associative array to an array of [key, value] pairs.


## string

- [split](https://github.com/tarsana/functional/blob/master/docs/string.md#split) - Curried version of `explode`.

- [join](https://github.com/tarsana/functional/blob/master/docs/string.md#join) - Curried version of `implode`.

- [replace](https://github.com/tarsana/functional/blob/master/docs/string.md#replace) - Curried version of `str_replace`.

- [regReplace](https://github.com/tarsana/functional/blob/master/docs/string.md#regReplace) - Curried version of `preg_replace`.

- [upperCase](https://github.com/tarsana/functional/blob/master/docs/string.md#upperCase) - Alias of `strtoupper`.

- [lowerCase](https://github.com/tarsana/functional/blob/master/docs/string.md#lowerCase) - Alias of `strtolower`.

- [camelCase](https://github.com/tarsana/functional/blob/master/docs/string.md#camelCase) - Gets the camlCase version of a string.

- [snakeCase](https://github.com/tarsana/functional/blob/master/docs/string.md#snakeCase) - Gets the snake-case of the string using `$delimiter` as separator.

- [startsWith](https://github.com/tarsana/functional/blob/master/docs/string.md#startsWith) - Checks if `$string` starts with `$token`.

- [endsWith](https://github.com/tarsana/functional/blob/master/docs/string.md#endsWith) - Checks if `$string` ends with `$token`.

- [test](https://github.com/tarsana/functional/blob/master/docs/string.md#test) - Checks if a string matches a regular expression.

- [match](https://github.com/tarsana/functional/blob/master/docs/string.md#match) - Performs a global regular expression match
and returns array of results.

- [occurences](https://github.com/tarsana/functional/blob/master/docs/string.md#occurences) - Curried version of `substr_count` with changed order of parameters,

- [chunks](https://github.com/tarsana/functional/blob/master/docs/string.md#chunks) - Splits a string into chunks without spliting any group surrounded with some specified characters.


## list

- [map](https://github.com/tarsana/functional/blob/master/docs/list.md#map) - Curried version of `array_map`.

- [chain](https://github.com/tarsana/functional/blob/master/docs/list.md#chain) - Applies a function to items of the array and concatenates the results.

- [filter](https://github.com/tarsana/functional/blob/master/docs/list.md#filter) - Curried version of `array_filter` with modified order of arguments.

- [reduce](https://github.com/tarsana/functional/blob/master/docs/list.md#reduce) - Curried version of `array_reduce` with modified order of
arguments ($callback, $initial, $list).

- [each](https://github.com/tarsana/functional/blob/master/docs/list.md#each) - Applies the callback to each item and returns the original list.

- [head](https://github.com/tarsana/functional/blob/master/docs/list.md#head) - Returns the first item of the given array or string.

- [last](https://github.com/tarsana/functional/blob/master/docs/list.md#last) - Returns the last item of the given array or string.

- [init](https://github.com/tarsana/functional/blob/master/docs/list.md#init) - Returns all but the last element of the given array or string.

- [tail](https://github.com/tarsana/functional/blob/master/docs/list.md#tail) - Returns all but the first element of the given array or string.

- [reverse](https://github.com/tarsana/functional/blob/master/docs/list.md#reverse) - Alias of `array_reverse()` and `strrev()`.

- [length](https://github.com/tarsana/functional/blob/master/docs/list.md#length) - Alias for `count()` and `strlen()`.

- [allSatisfies](https://github.com/tarsana/functional/blob/master/docs/list.md#allSatisfies) - Checks if the `$predicate` is verified by **all** items of the array.

- [anySatisfies](https://github.com/tarsana/functional/blob/master/docs/list.md#anySatisfies) - Checks if the `$predicate` is verified by **any** item of the array.

- [concat](https://github.com/tarsana/functional/blob/master/docs/list.md#concat) - Concatenates two arrays or strings.

- [concatAll](https://github.com/tarsana/functional/blob/master/docs/list.md#concatAll) - Concatenates a list of arrays or strings.

- [insert](https://github.com/tarsana/functional/blob/master/docs/list.md#insert) - Inserts an item at some position into an array or a substring into a string.

- [insertAll](https://github.com/tarsana/functional/blob/master/docs/list.md#insertAll) - Same as `insert` but inserts an array instead of a single item.

- [append](https://github.com/tarsana/functional/blob/master/docs/list.md#append) - Appends an item to an array or a substring to a string.

- [prepend](https://github.com/tarsana/functional/blob/master/docs/list.md#prepend) - Inserts an item at the begining of an array or a substring at the begining of a string.

- [take](https://github.com/tarsana/functional/blob/master/docs/list.md#take) - Takes a number of elements from an array or a number of characters from a string.

- [takeWhile](https://github.com/tarsana/functional/blob/master/docs/list.md#takeWhile) - Takes elements from an array while they match the given predicate.

- [takeLastWhile](https://github.com/tarsana/functional/blob/master/docs/list.md#takeLastWhile) - Same as `takeWhile` but taking elements from the end of the array.

- [takeUntil](https://github.com/tarsana/functional/blob/master/docs/list.md#takeUntil) - Takes elements from an array **until** the predicate
is satisfied, not including the satisfying element.

- [takeLastUntil](https://github.com/tarsana/functional/blob/master/docs/list.md#takeLastUntil) - Same as `takeUntil` but takes elements from the end of the array.

- [remove](https://github.com/tarsana/functional/blob/master/docs/list.md#remove) - Removes a number of elements from an array.

- [removeWhile](https://github.com/tarsana/functional/blob/master/docs/list.md#removeWhile) - Removes elements from an array while they match the given predicate.

- [removeLastWhile](https://github.com/tarsana/functional/blob/master/docs/list.md#removeLastWhile) - Same as `removeWhile` but removes elements from the end of the array.

- [removeUntil](https://github.com/tarsana/functional/blob/master/docs/list.md#removeUntil) - Removes elements from an array **until** the predicate
is satisfied, not removing the satisfying element.

- [removeLastUntil](https://github.com/tarsana/functional/blob/master/docs/list.md#removeLastUntil) - Same as `removeUntil` but removes elements from the end of the array.

- [fromPairs](https://github.com/tarsana/functional/blob/master/docs/list.md#fromPairs) - Converts an array of (key, value) pairs to an object (instance of `stdClass`).

- [slices](https://github.com/tarsana/functional/blob/master/docs/list.md#slices) - Gets an array of slices of size `$size` from an array.

- [contains](https://github.com/tarsana/functional/blob/master/docs/list.md#contains) - Checks if an array contains an item.

- [findIndex](https://github.com/tarsana/functional/blob/master/docs/list.md#findIndex) - Returns the position/key of the first item satisfying the
predicate in the array or null if no such element is found.

- [findLastIndex](https://github.com/tarsana/functional/blob/master/docs/list.md#findLastIndex) - Returns the position/key of the last item satisfying the
predicate in the array or null if no such element is found.

- [find](https://github.com/tarsana/functional/blob/master/docs/list.md#find) - Returns the first item satisfying the predicate in
the array or null if no such element is found.

- [findLast](https://github.com/tarsana/functional/blob/master/docs/list.md#findLast) - Returns the last item satisfying the predicate in
the array or null if no such element is found.

- [indexOf](https://github.com/tarsana/functional/blob/master/docs/list.md#indexOf) - Returns the index of an item/substring in a list/string.

- [lastIndexOf](https://github.com/tarsana/functional/blob/master/docs/list.md#lastIndexOf) - Same as `indexOf` but returns the key/position/name of the last item/substring/attribute.

- [uniqueBy](https://github.com/tarsana/functional/blob/master/docs/list.md#uniqueBy) - Removes duplicates from a list.

- [unique](https://github.com/tarsana/functional/blob/master/docs/list.md#unique) - Alias of `F\uniqueBy(F\equals())`.

- [groupBy](https://github.com/tarsana/functional/blob/master/docs/list.md#groupBy) - Converts an array to an associative array, based on the result of calling `$fn`
on each element, and grouping the results according to values returned.

- [pairsFrom](https://github.com/tarsana/functional/blob/master/docs/list.md#pairsFrom) - Makes list of pairs from two lists.

- [sort](https://github.com/tarsana/functional/blob/master/docs/list.md#sort) - Returns a copy of the given list, ordered using the given comparaison function.


## math

- [plus](https://github.com/tarsana/functional/blob/master/docs/math.md#plus) - Computes `$x + $y`.

- [minus](https://github.com/tarsana/functional/blob/master/docs/math.md#minus) - Computues `$x - $y`.

- [negate](https://github.com/tarsana/functional/blob/master/docs/math.md#negate) - Computes `- $x`.

- [multiply](https://github.com/tarsana/functional/blob/master/docs/math.md#multiply) - Computes `$x * $y`.

- [divide](https://github.com/tarsana/functional/blob/master/docs/math.md#divide) - Computes `$x / $y`.

- [modulo](https://github.com/tarsana/functional/blob/master/docs/math.md#modulo) - Computes `$x % $y`.

- [sum](https://github.com/tarsana/functional/blob/master/docs/math.md#sum) - Computes the sum of an array of numbers.

- [product](https://github.com/tarsana/functional/blob/master/docs/math.md#product) - Computes the product of an array of numbers.

- [min](https://github.com/tarsana/functional/blob/master/docs/math.md#min) - Computes the minimum of two numbers.

- [minBy](https://github.com/tarsana/functional/blob/master/docs/math.md#minBy) - Computes the minimum of two elements using a function.

- [max](https://github.com/tarsana/functional/blob/master/docs/math.md#max) - Computes the maximum of two numbers.

- [maxBy](https://github.com/tarsana/functional/blob/master/docs/math.md#maxBy) - Computes the maximum of two elements using a function.
