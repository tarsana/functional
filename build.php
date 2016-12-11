<?php namespace Tarsana\Functional;
/**
 * This script parses the source files using [dox](https://github.com/tj/dox)
 * and generates the unit tests and documentation files.
 */
require __DIR__ . '/vendor/autoload.php';

// List of function modules source files.
$modulesFiles = [
    'src/Math.php',
    'src/Object.php',
    'src/Functions.php',
    'src/Operators.php',
    'src/String.php',
    'src/Common.php',
    'src/List.php'
];

/**
 * Custom Types:
 *  DoxBlock :: {
 *     tags: [{
 *         type: String,
 *         string: String,
 *         types: [String],
 *         name: String,
 *         description: String
 *         ...
 *     }],
 *     description: {
 *         full: String,
 *         summary: String,
 *         body: String
 *     },
 *     code: String,
 *     ctx: {
 *         type: String,
 *         name: String,
 *         ...
 *     }
 *     isPrivate:
 *     isEvent:
 *     isConstructor:
 *     line:
 *     ignore:
 *  }
 *
 * Block :: {
 *     type: file|function|class|method
 *     name: String // DoxBlock.ctx.name
 *     params: [{type: String, name: String}]
 *     return: String
 *     signature: String
 *     description: String
 *     summary: String
 *     internal: Boolean
 *     ignore: Boolean
 *     code: String
 * }
 *
 * Module :: {
 *     path: String
 *     name: String
 *     docsPath: String
 *     testsPath: String
 *     blocks: [Block]
 *     docs: String
 *     tests: String
 *     testsFooter: String
 * }
 */

/**
 * The entry point.
 *
 * @signature [String] -> IO
 * @param  array $modules
 * @return void
 */
function build_main($modules) {
    each(_f('build_module'), get_modules());
}

/**
 * Extracts the modules files from composer.json.
 *
 * @signature [String]
 * @return array
 */
function get_modules() {
    $composer = json_decode(file_get_contents(__DIR__.'/composer.json'));
    return $composer->autoload->files;
}

/**
 * Generates unit tests and documentation for a module.
 *
 * @signature String -> IO
 * @param  string $path
 * @return void
 */
function build_module($path) {
    apply(process_of([
        'module_of',
        'generate_docs',
        'generate_tests',
        'write_module'
    ]), [$path]);
}

/**
 * Writes the module's docs and tests.
 *
 * @signature Module -> IO
 * @param  object $module
 * @return void
 */
function write_module($module) {
    if ($module->docs) {
        $docsDir  = dirname($module->docsPath);
        if (!is_dir($docsDir))
            mkdir($docsDir, 0777, true);
        file_put_contents($module->docsPath,  $module->docs);
    }
    if ($module->tests) {
        $testsDir = dirname($module->testsPath);
        if (!is_dir($testsDir))
            mkdir($testsDir, 0777, true);
        file_put_contents($module->testsPath, $module->tests);
    }
}

/**
 * Creates a module from a path.
 *
 * @signature String -> Module
 * @param  string $path
 * @return object
 */
function module_of($path) {
    return apply(process_of([
        'fill_name',
        'fill_docs_path',
        'fill_tests_path',
        'fill_blocks'
    ]), [(object)['path' => $path]]);
}

/**
 * Fills documentation file path based on source file path.
 * 'src/xxx.php' -> 'docs/xxx.md'
 *
 * @signature Module -> Module
 * @param  object $module
 * @return object
 */
function fill_docs_path($module) {
    $module->docsPath = replace(['src', '.php'], ['docs', '.md'], $module->path);
    return $module;
}

/**
 * Fills tests file path based on source file path.
 * 'src/xxx.php' -> 'tests/xxxTest.php'
 *
 * @signature Module -> Module
 * @param  object $module
 * @return object
 */
function fill_tests_path($module) {
    $name = ucfirst(camelCase($module->name));
    $dir = 'tests' . remove(3, dirname($module->path));
    $module->testsPath = "{$dir}/{$name}Test.php";
    return $module;
}

/**
 * Fills the name of the Module based on the path.
 * 'src/xxx/aaa.php' -> 'aaa'
 *
 * @signature Module -> Module
 * @param  object $module
 * @return object
 */
function fill_name($module) {
    $module->name = apply(pipe(split('/'), last(), split('.'), head()), [$module->path]);
    return $module;
}

/**
 * Fills the blocks of the Module based on the path.
 *
 * @signature Module -> Module
 * @param  array $module
 * @return array
 */
function fill_blocks($module) {
    $module->blocks = apply(pipe(
        prepend('dox -r < '), // "dox -r < src/...php"
        'shell_exec',         // "[{...}, ...]"
        'json_decode',        // [DoxBlock]
        map(_f('make_block'))
    ), [$module->path]);
    return $module;
}

/**
 * Converts a DoxBlock to a Block.
 *
 * @signature DoxBlock -> Block
 * @param  object $doxBlock
 * @return object
 */
function make_block($doxBlock) {
    $tags = groupBy(get('name'), tags_of($doxBlock));

    $type = 'function';
    if (has('file', $tags)) $type = 'file';
    if (has('class', $tags)) $type = 'class';
    if (has('method', $tags)) $type = 'method';

    $params = map(function($tag){
        $parts = split(' ', get('value', $tag));
        return [
            'type' => $parts[0],
            'name' => $parts[1]
        ];
    }, get('param', $tags) ?: []);

    $return = getPath(['return', 0, 'value'], $tags);
    $signature = getPath(['signature', 0, 'value'], $tags);
    return (object) [
        'type' => $type,
        'name' => getPath(['ctx', 'name'], $doxBlock),
        'params' => $params,
        'return' => $return,
        'signature' => $signature,
        'description' => getPath(['description', 'full'], $doxBlock),
        'summary' => getPath(['description', 'summary'], $doxBlock),
        'internal' => has('internal', $tags),
        'ignore' => has('ignore', $tags)
        // 'code' => get('code', $doxBlock)
    ];
}

/**
 * Returns an array of tags, each having a name and a value.
 *
 * @signature DoxBlock -> [{name: String, value: String}]
 * @param  object $doxBlock
 * @return array
 */
function tags_of($doxBlock) {
    return map(function($tag){
        return (object) [
            'name'  => $tag->type,
            'value' => $tag->string
        ];
    }, $doxBlock->tags);
}

/**
 * Generates documentation contents for a module.
 *
 * @signature Module -> Module
 * @param  object $module
 * @return object
 */
function generate_docs($module) {
    $module->docs = '';
    if (startsWith('_', $module->name))
        return $module;
    return apply(process_of([
        'generate_docs_header',
        'generate_docs_sommaire',
        'generate_docs_contents'
    ]), [$module]);
}

/**
 * Generates documentation header.
 *
 * @signature Module -> Module
 * @param  object $module
 * @return object
 */
function generate_docs_header($module) {
    $name = $module->name;
    $description = get('description', head($module->blocks));
    $module->docs .= "#{$name}\n\n{$description}\n\n";
    return $module;
}

/**
 * Generates documentation table of contents.
 *
 * @signature Module -> Module
 * @param  object $module
 * @return object
 */
function generate_docs_sommaire($module) {
    $blocks = filter (
        satisfiesAll(['ignore' => not(), 'internal' => not(), 'type' => equals('function')]),
        $module->blocks
    );
    $items = map(_f('generate_docs_sommaire_item'), $blocks);
    $module->docs .= join('', $items);
    return $module;
}

/**
 * Generates an item of the documentation's table of contents.
 *
 * @signature Block -> String
 * @param  object $block
 * @return string
 */
function generate_docs_sommaire_item($block) {
    $title = get('name', $block);
    $link  = lowerCase($title);
    return "- [{$title}](#{$link}) {$block->summary}\n\n";
}

/**
 * Generates documentation contents.
 *
 * @signature Module -> Module
 * @param  object $module
 * @return object
 */
function generate_docs_contents($module) {
    $blocks = filter (
        satisfiesAll(['ignore' => not(), 'internal' => not()]),
        $module->blocks
    );
    $contents = map(_f('generate_docs_contents_item'), $blocks);
    $module->docs .= join('', $contents);
    return $module;
}

/**
 * Generates an item of the documentation's contents.
 *
 * @signature Block -> String
 * @param  object $block
 * @return string
 */
function generate_docs_contents_item($block) {
    if ($block->type != 'function')
        return '';
    $params = join(', ', map(pipe(values(), join(' ')), get('params', $block)));
    $return = get('return', $block);
    $prototype = "```php\n{$block->name}({$params}) : {$return}\n```\n\n";
    $signature = '';
    if ($block->signature)
        $signature = "```\n{$block->signature}\n```\n\n";
    return "# {$block->name}\n\n{$prototype}{$signature}{$block->description}\n\n";
}

/**
 * Generates tests contents for a module.
 *
 * @signature Module -> Module
 * @param  object $module
 * @return object
 */
function generate_tests($module) {
    $module->tests = '';
    $module->testsFooter = '';
    return apply(process_of([
        'generate_tests_header',
        'generate_tests_contents',
        'generate_tests_footer'
    ]), [$module]);
}

/**
 * Generates module's tests header.
 *
 * @signature Module -> Module
 * @param  object $module
 * @return object
 */
function generate_tests_header($module) {
    $namespace = "Tarsana\UnitTests\Functional";
    $additionalNamespace = replace("/", "\\", remove(6, dirname($module->testsPath)));
    if ($additionalNamespace)
        $namespace .= "\\" . $additionalNamespace;
    $name = remove(-4, last(split("/", $module->testsPath)));
    $module->tests .= "<?php namespace {$namespace};\n\nuse Tarsana\Functional as F;\n\nclass {$name} extends \Tarsana\UnitTests\Functional\UnitTest {\n";
    return $module;
}

/**
 * Generates module's tests contents.
 *
 * @signature Module -> Module
 * @param  object $module
 * @return object
 */
function generate_tests_contents($module) {
    $blocks = filter (
        satisfiesAll(['ignore' => not()]),
        $module->blocks
    );
    $contents = join("\n", map(function($block) use($module) {
        return generate_tests_contents_item($block, $module);
    }, $blocks));
    if (trim($contents) != '')
        $module->tests .= $contents;
    else
        $module->tests = '';
    return $module;
}

/**
 * Generates a test for a module.
 *
 * @signature Block -> Module -> String
 * @param  object $block
 * @param  object $module
 * @return string
 */
function generate_tests_contents_item($block, $module) {
    if ($block->type != 'function')
        return '';

    $code = apply(pipe(
        _f('code_from_description'),
        chunks("\"\"''{}[]()", "\n"),
        map(function($part) use($module) {
            return add_assertions($part, $module);
        }),
        filter(pipe('trim', notEq(''))),
        chain(split("\n")),
        map(prepend("\t\t")),
        join("\n")
    ), [$block]);

    if ('' == trim($code))
        return '';
    return prepend("\tpublic function test_{$block->name}() {\n",
        append("\n\t}\n", $code)
    );
}

/**
 * Extracts the code snippet from the description of a block.
 *
 * @signature Block -> String
 * @param  object $block
 * @return string
 */
function code_from_description($block) {
    $description = get('description', $block);
    if (!contains('```php', $description))
        return '';
    $code = remove(7 + indexOf('```php', $description), $description);
    return remove(-4, trim($code));
}

/**
 * Adds assertions to a part of the code.
 *
 * @signature String -> String
 * @param  string $part
 * @return string
 */
function add_assertions($part, $module) {
    if (contains('; //=> ', $part)) {
        $pieces = split('; //=> ', $part);
        $part = '$this->assertEquals(' . $pieces[1] . ', ' . $pieces[0] . ');';
    }
    elseif (startsWith('class ', $part) || startsWith('function ', $part)) {
        $module->testsFooter .= $part . "\n\n";
        $part = '';
    }
    return $part;
}

/**
 * Generates module's tests footer.
 *
 * @signature Module -> Module
 * @param  object $module
 * @return object
 */
function generate_tests_footer($module) {
    if ($module->tests)
        $module->tests .= "}\n\n{$module->testsFooter}";
    return $module;
}

/**
 * process_of(['f1', 'f2']) == pipe(_f('f1'), _f('f2'));
 *
 * @signature [String] -> Function
 * @param array $fns
 * @return callable
 */
function process_of($fns) {
    return apply(_f('pipe'), map(_f('_f'), $fns));
}

/**
 * Dump a variable and returns it.
 *
 * @signature a -> a
 * @param  mixed $something
 * @return mixed
 */
function log() {
    $log = function($something) {
        var_dump($something);
        return $something;
    };
    return apply(curry($log), func_get_args());
}

// Run the build
build_main($modulesFiles);
