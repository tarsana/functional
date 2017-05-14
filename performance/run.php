<?php

/**
 * Returns the list of scripts to compare.
 *
 * @return array
 */
function scripts() {
    return [
        __DIR__ . '/scripts/imperative.php',
        __DIR__ . '/scripts/functional.php',
        __DIR__ . '/scripts/object-oriented.php',
    ];
}

/**
 * Returs the list of tests to run.
 *
 * @return array
 */
function tests() {
    static $tests = null;
    if (null == $tests) {
        $tests = [
            (object) [
                'input' => __DIR__ . '/tests/1k-words.input.txt',
                'output' => trim(file_get_contents(__DIR__ . '/tests/1k-words.output.txt'))
            ],
            (object) [
                'input' => __DIR__ . '/tests/10k-words.input.txt',
                'output' => trim(file_get_contents(__DIR__ . '/tests/10k-words.output.txt'))
            ],
            (object) [
                'input' => __DIR__ . '/tests/100k-words.input.txt',
                'output' => trim(file_get_contents(__DIR__ . '/tests/100k-words.output.txt'))
            ],
        ];
    }
    return $tests;
}

/**
 * Runs the scripts with the tests and shows the results.
 *
 * @return void
 */
function main() {
    foreach (scripts() as $path) {
        echo nameOf($path), ":\n";
        foreach (tests() as $test) {
            echo "  ", nameOf($test->input), ": ";
            if (!passes($path, $test)) {
                echo "Failed!\n";
            } else {
                echo averageTime(3, $path, $test), "\n";
            }
        }
    }
}

/**
 * Gets the name of a file without extension from its path.
 *
 * @param  string $path
 * @return string
 */
function nameOf($path) {
    $name = substr($path, strrpos($path, '/') + 1);
    return substr($name, 0, strpos($name, '.'));
}

/**
 * Runs the given script `$n` times and returns the average running time.
 *
 * @param  int $n
 * @param  string $path
 * @param  object $test
 * @return int
 */
function averageTime($n, $path, $test) {
    $s = 0;
    for ($i=0; $i < $n; $i++) {
        $s =+ execute($path, $test->input);
    }
    return $s / $n;
}

/**
 * Checks if running a PHP script with some input returns the expected output.
 *
 * @param  string $path
 * @param  array $test
 * @return bool
 */
function passes($path, $test) {
    return $test->output == trim(shell_exec("php {$path} < \"{$test->input}\""));
}

/**
 * Runs a PHP file with specific standard input
 * and returns its running time in miliseconds.
 *
 * @param  string $path
 * @return array
 */
function execute($path, $input) {
    $start = microtime(true);
    shell_exec("php {$path} < \"{$input}\"");
    return 1000 * (microtime(true) - $start);
}

main();
