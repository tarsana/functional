<?php namespace Demo;
/**
 * This script is an example of usage Tarsana\Functional
 * It is used to generate the Reference documentation of
 * functions in the library as markdown files.
 * This is using [dox](https://github.com/tj/dox) to
 * parse sources files and extract documentation from comments.
 * This script is not a perfect example of Functional Programming,
 * it simply shows a real use case of this library.
 */
require __DIR__ . '/vendor/autoload.php';

use Tarsana\Functional as F;

// Reads the list of sources files from 'composer.json'
// * -> IO [String]
function modules() {
    $composer = json_decode(file_get_contents(__DIR__.'/composer.json'));
    return $composer->autoload->files;
}

// Extracts tags with specific type from a docblock.
// String -> Object -> [name => String, description => String, string => String]
function tags($type, $data) {
    return F\filter(function($tag) use($type) {
        return $tag->type == $type;
    }, $data->tags);
}

// Extracts list of arguments from a docblock.
// Object -> [Arg]
/**
 * @type Arg
 * Argument of a function.
 *
 * @field name String
 * @field type String
 */
function argsOf($data) {
    return F\map(function($tag){
        return (object) [
            'type' => $tag->name,
            'name' => $tag->description
        ];
    }, tags('param', $data));
}

// Extracts signatures of a function.
// Object -> [String]
function signaturesOf($data) {
    return F\map(function($tag){
        return $tag->string;
    }, tags('signature', $data));
}

// Extracts the return type of a function.
// Object -> String
function returnOf($data) {
    $returns = tags('return', $data);
    return (F\length($returns) > 0)
        ? $returns[0]->description
        : null;
}

// Extracts the type of a block
// Object -> String
function typeOf($data) {
    if (isset($data->ctx->type))
        return $data->ctx->type;
    if (F\length(tags('var', $data)) > 0)
        return 'attr';
    if (F\length(tags('return', $data)) > 0)
        return 'method';
}

// Extract keywords
// Object -> [String]
function keywords($data) {
    if (!isset($data->code)) {
        return [];
    }
    $size = strpos($data->code, '(');
    if ($size === false)
        $size = strlen($data->code);
    $keywords = F\pipe(
        F\take($size),
        F\split(' '),
        F\map('trim'),
        F\filter(F\notEq(''))
    );
    return $keywords($data->code);
}

// Object -> DocBlock
/**
 * @type DocBlock
 * Documentation block of a function.
 *
 * @field type String
 * @field name String
 * @field args [Arg]
 * @field return String
 * @field signatures [String]
 * @field description String
 * @field is_internal Boolean
 * @field is_static Boolean
 */
function block($data) {
    $keywords = keywords($data);
    return (object) [
        'type' => typeOf($data),
        'name' => isset($data->ctx->name) ? $data->ctx->name : F\last($keywords),
        'args' => argsOf($data),
        'return' => returnOf($data),
        'signatures' => signaturesOf($data),
        'description' => $data->description->full,
        'is_static' => in_array('static', $keywords),
        'is_internal' => !in_array('public', $keywords) || (0 < F\length(tags('internal', $data)))
    ];
}

// Get a markdown code block
// String -> String -> String
function code($lang, $text) {
    if(trim($text) == '')
        return '';
    return "```{$lang}\n{$text}\n```";
}

// Gets the markdown of a function/method/class
// DocBlock -> String
function markdown($fn) {
    if ($fn->type == 'class') {
        return $fn->description;
    } else {
        $args = F\map(function($arg) {
            return $arg->type . ' ' . $arg->name;
        }, $fn->args);
        $proto = $fn->name . '('. F\join(', ', $args) .') : ' . $fn->return;
        return F\join("\n\n", [
            "## {$fn->name}",
            code('php', $proto),
            code('', F\join("\n", $fn->signatures)),
            $fn->description
        ]);
    }
}

// Generates documentation for a module of functions
// String -> IO
function generateModule($file) {
    $content = F\pipe(
        F\map('Demo\\block'),
        F\filter(function($block){
            return $block->type == 'function' && !$block->is_internal;
        }),
        F\map('Demo\\markdown'),
        function($parts) use ($file) {
            $name = F\replace(['src/', '.php'], '', $file);
            return array_merge(["# {$name}"], $parts);
        },
        F\join("\n\n")
    );

    file_put_contents (
        F\replace(['src', '.php'], ['docs', '.md'], $file),
        $content(json_decode(shell_exec("dox -r < {$file}")))
    );
}

// Generates documentation for a class
// String -> IO
function generateClass($name) {
    $content = F\pipe(
        F\map('Demo\\block'),
        F\filter(function($block){
            return in_array($block->type, ['method', 'class']) && !$block->is_internal;
        }),
        f\map(function($block) use ($name) {
            if ($block->type == 'method') {
                $block->name = ($block->is_static ? $name . '::' : '') . $block->name;
            }
            return $block;
        }),
        F\map('Demo\\markdown'),
        function($parts) use ($name) {
            return array_merge(["# {$name}"], $parts);
        },
        F\join("\n\n"),
        F\regReplace('/\\n+/', "\n")
    );

    file_put_contents (
        "docs/{$name}.md",
        $content(json_decode(shell_exec("dox -r < src/{$name}.php")))
    );
}

// The entry point
F\each('Demo\\generateModule', modules());
F\each('Demo\\generateClass', ['Stream', 'Error']);
