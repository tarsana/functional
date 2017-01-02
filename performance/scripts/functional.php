<?php
require __DIR__.'/../../vendor/autoload.php';

use Tarsana\Functional as F;
use Tarsana\Functional\Stream;

Stream::operation('read', 'String -> String', 'file_get_contents');
Stream::operation('write', 'String -> String -> Any', 'file_put_contents');
Stream::operation('trim', 'String -> String');
Stream::operation('countValues', 'List|Array -> Array', 'array_count_values');

// Stream::of(__DIR__.'/../tests/10k-words.input.txt')
Stream::of('php://stdin')
    ->read()                                                // ' lorem, ipsum ...'
    ->regReplace('/[^a-zA-Z0-9]+/', ' ')                    // ' lorem ipsum ...'
    ->trim()                                                // 'lorem ipsum ...'
    ->split(' ')                                            // ['lorem', 'ipsum', ...]
    ->countValues()                                         // ['lorem' => 2, 'ipsum' => 3, ...]
    ->toPairs()                                             // [['lorem', 2], ['ipsum', 3], ...]
    ->groupBy(F\get(1))                                     // [2 => [['lorem', 2], ...], 3 => [['ipsum', 3], ...]]
    ->map(F\map(F\get(0)))                                  // [2 => ['lorem', 'foo', ...], 3 => ['ipsum', ...]]
    ->map(F\sort(function($w1, $w2) {                       // [2 => ['foo', 'lorem', ...], 3 => ['bar', 'ipsum', ...]]
        return strcmp($w1, $w2) < 0;
    }))
    ->toPairs()                                             // [[2, ['foo', 'lorem', ...]], [3, ['bar', 'ipsum', ...]], ...]
    ->sort(function($pair1, $pair2) {                       // [[3, ['bar', 'ipsum', ...]], [2, ['foo', 'lorem', ...]], ...]
        return F\get(0, $pair2) < F\get(0, $pair1);
    })
    ->map(function($pair) {                                 // ['3: bar, ipsum, ...', '2: foo, lipsum, ...']
        return F\get(0, $pair) . ': ' . F\join(', ', F\get(1, $pair));
    })
    ->join("\n")
    // ->write(__DIR__.'/../tests/output.txt')
    ->write('php://stdout')
    ->result();
