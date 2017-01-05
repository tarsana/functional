<?php
// $text = file_get_contents(__DIR__.'/../tests/1M-words.input.txt');
$text = file_get_contents('php://stdin');
$text = trim(preg_replace('/[^a-zA-Z0-9]+/', ' ', $text));
$text = array_count_values(explode(' ', $text));

$words = [];
foreach ($text as $word => $occ) {
    if (! isset($words[$occ])) {
        $words[$occ] = [];
    }
    $words[$occ][] = $word;
}

$text = '';

foreach ($words as $occ => $list) {
    sort($words[$occ]);
}

krsort($words);

foreach ($words as $occ => $list) {
    $list = implode(', ', $list);
    $text .= "{$occ}: {$list}\n";
}

echo $text;
// file_put_contents(__DIR__.'/../tests/1M-words.output.txt', $text);
