<?php namespace Tarsana\Functional;
/**
 * This file contains some useful Math functions.
 */

function reduce($fn, $initial, $items) {
    return array_reduce($items, $fn, $initial);
}
