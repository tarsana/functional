<?php namespace Tarsana\UnitTests\Functional\Internal;

use Tarsana\Functional as F;

class StreamTest extends \Tarsana\UnitTests\Functional\UnitTest {






	public function test__stream_operation() {
		// Using function name
		$length = F\_stream_operation('length', 'List|Array -> Number', 'count');
		$this->assertEquals(['name' => 'length', 'signatures' => [['List', 'Number'], ['Array', 'Number']], 'fn' => 'count'], $length);
		// Using closure
		$increment = function($x) {
		    return 1 + $x;
		};
		$operation = F\_stream_operation('increment', 'Number -> Number', $increment);
		$this->assertEquals(['name' => 'increment', 'signatures' => [['Number', 'Number']], 'fn' => $increment], $operation);
		// Without callable
		$this->assertEquals(['name' => 'count', 'signatures' => [['List', 'Number']], 'fn' => 'count'], F\_stream_operation('count', 'List -> Number'));
		// Invalid signature
		$this->assertErrorThrown(function() {
			F\_stream_operation('count', 'Number'); 
		},
		"Stream: invalid signature 'Number' it should follow the syntax 'TypeArg1 -> TypeArg2 -> ... -> ReturnType' and types to use are Boolean, Number, String, Resource, Function, List, Array, Object, Any");
		// Invalid callable
		$this->assertErrorThrown(function() {
			F\_stream_operation('foo', 'List -> Number'); 
		},
		"Stream: unknown callable 'foo'");
	}

	public function test__stream_make_signatures() {
		$this->assertEquals([
		    ['Number', 'Number', 'String', 'Number'],
		    ['List', 'Number', 'String', 'Number'],
		    ['Number', 'Number', 'Array', 'Number'],
		    ['List', 'Number', 'Array', 'Number']
		], F\_stream_make_signatures('Number|List -> Number -> String|Array -> Number'));
		$this->assertErrorThrown(function() {
			F\_stream_make_signatures('List'); 
		},
		"Stream: invalid signature 'List' it should follow the syntax 'TypeArg1 -> TypeArg2 -> ... -> ReturnType' and types to use are Boolean, Number, String, Resource, Function, List, Array, Object, Any");
		$this->assertErrorThrown(function() {
			F\_stream_make_signatures('List -> Foo'); 
		},
		"Stream: invalid signature 'List -> Foo' it should follow the syntax 'TypeArg1 -> TypeArg2 -> ... -> ReturnType' and types to use are Boolean, Number, String, Resource, Function, List, Array, Object, Any");
	}

	public function test__stream_ensure_type() {
		$this->assertEquals('List', F\_stream_ensure_type('List -> Bar', 'List'));
		$this->assertErrorThrown(function() {
			F\_stream_ensure_type('List -> Bar', 'Bar'); 
		},
		"Stream: invalid signature 'List -> Bar' it should follow the syntax 'TypeArg1 -> TypeArg2 -> ... -> ReturnType' and types to use are Boolean, Number, String, Resource, Function, List, Array, Object, Any");
	}


	public function test__stream() {
		$map = F\map();
		$operations = [
		    F\_stream_operation('length', 'List|Array -> Number', 'count'),
		    F\_stream_operation('length', 'String -> Number', 'strlen'),
		    F\_stream_operation('map', 'Function -> List -> List', $map)
		];
		$this->assertEquals([
		    'data' => 11,
		    'type' => 'Number',
		    'result' => 11,
		    'resolved' => true,
		    'operations' => [
		        'length' => [
		            [
		                'name' => 'length',
		                'signatures' => [['List', 'Number'], ['Array', 'Number']],
		                'fn' => 'count'
		            ],
		            [
		                'name' => 'length',
		                'signatures' => [['String', 'Number']],
		                'fn' => 'strlen'
		            ]
		        ],
		        'map' => [
		            [
		                'name' => 'map',
		                'signatures' => [['Function', 'List', 'List']],
		                'fn' => $map
		            ]
		        ]
		    ],
		    'transformations' => []
		], F\_stream($operations, 11));
	}

	public function test__stream_validate_operations() {
		$this->assertEquals([
		    [
		        'name' => 'length',
		        'signatures' => [['List', 'Number'], ['Array', 'Number']],
		        'fn' => 'count'
		    ],
		    [
		        'name' => 'length',
		        'signatures' => [['String', 'Number']],
		        'fn' => 'strlen'
		    ]
		], F\_stream_validate_operations([
		    [
		        'name' => 'length',
		        'signatures' => [['List', 'Number'], ['Array', 'Number']],
		        'fn' => 'count'
		    ],
		    [
		        'name' => 'length',
		        'signatures' => [['String', 'Number']],
		        'fn' => 'strlen'
		    ]
		]));
		$this->assertErrorThrown(function() {
			F\_stream_validate_operations([
		    [
		        'name' => 'length',
		        'signatures' => [['List', 'Number'], ['Array', 'Number']],
		        'fn' => 'count'
		    ],
		    [
		        'name' => 'length',
		        'signatures' => [['String', 'Number'], ['List', 'Number']],
		        'fn' => 'strlen'
		    ]
		]); 
		},
		"Stream: signatures of the operation 'length' are duplicated or ambiguous");
	}

	public function test__stream_apply_operation() {
		$operations = [
		    F\_stream_operation('length', 'List|Array -> Number', 'count'),
		    F\_stream_operation('length', 'String -> Number', 'strlen'),
		    F\_stream_operation('map', 'Function -> List -> List', F\map())
		];
		$stream = F\_stream($operations, [1, 2, 3]);
		$this->assertEquals([
		    'data' => [1, 2, 3],
		    'type' => 'Number',
		    'result' => null,
		    'resolved' => false,
		    'operations' => [
		        'length' => [
		            [
		                'name' => 'length',
		                'signatures' => [['List', 'Number'], ['Array', 'Number']],
		                'fn' => 'count'
		            ],
		            [
		                'name' => 'length',
		                'signatures' => [['String', 'Number']],
		                'fn' => 'strlen'
		            ]
		        ],
		        'map' => [
		            [
		                'name' => 'map',
		                'signatures' => [['Function', 'List', 'List']],
		                'fn' => F\map()
		            ]
		        ]
		    ],
		    'transformations' => [
		        [
		            'operations' => [[
		                'name' => 'length',
		                'signatures' => [['List', 'Number']],
		                'fn' => 'count'
		            ]],
		            'args' => []
		        ]
		    ]
		], F\_stream_apply_operation('length', [], $stream));
		$this->assertErrorThrown(function() use($stream) {
			F\_stream_apply_operation('foo', [], $stream); 
		},
		"Stream: call to unknown operation 'foo'");
		$this->assertErrorThrown(function() use($stream) {
			F\_stream_apply_operation('length', [5], $stream); 
		},
		"Stream: wrong arguments (Number, List) given to operation 'length'");
		$this->assertErrorThrown(function() use($stream) {
			F\_stream_apply_operation('map', [], $stream); 
		},
		"Stream: wrong arguments (List) given to operation 'map'");
		$this->assertErrorThrown(function() use($stream) {
			F\_stream_apply_operation('map', [[1, 2]], $stream); 
		},
		"Stream: wrong arguments (List, List) given to operation 'map'");
	}

	public function test__stream_split_operation_signatures() {
		$this->assertEquals([
		    [
		        'name' => 'length',
		        'signatures' => [['List', 'Number']],
		        'fn' => 'count'
		    ],
		    [
		        'name' => 'length',
		        'signatures' => [['Array', 'Number']],
		        'fn' => 'count'
		    ]
		], F\_stream_split_operation_signatures([
		    'name' => 'length',
		    'signatures' => [['List', 'Number'], ['Array', 'Number']],
		    'fn' => 'count'
		]));
	}

	public function test__stream_operation_is_applicable() {
		$isApplicable = F\_stream_operation_is_applicable(['Number', 'Number']);
		$this->assertEquals(true, $isApplicable(F\_stream_operation('add', 'Number -> Number -> Number', F\plus())));
		$this->assertEquals(false, $isApplicable(F\_stream_operation('length', 'List|Array|String -> Number', F\length())));
		$this->assertEquals(true, F\_stream_operation_is_applicable(
		    ['List'],
		    F\_stream_operation('length', 'List|Array|String -> Number', F\length())
		));
		$this->assertEquals(true, F\_stream_operation_is_applicable(
		    ['String'],
		    F\_stream_operation('length', 'List|Array|String -> Number', F\length())
		));
		$this->assertEquals(false, F\_stream_operation_is_applicable(
		    ['Number'],
		    F\_stream_operation('length', 'List|Array|String -> Number', F\length())
		));
		$this->assertEquals(true, F\_stream_operation_is_applicable(
		    ['Number', 'String'],
		    F\_stream_operation('fill', 'Number -> Any -> List', function(){})
		));
		$this->assertEquals(true, F\_stream_operation_is_applicable(
		    ['Any', 'String'],
		    F\_stream_operation('fill', 'Number -> Any -> List', function(){})
		));
	}

	public function test__stream_return_type_of_operation() {
		$this->assertEquals('Number', F\_stream_return_type_of_operation(F\_stream_operation(
		    'count', 'List -> Number'
		)));
		$this->assertEquals('String', F\_stream_return_type_of_operation(F\_stream_operation(
		    'count', 'List ->Function -> String'
		)));
		$this->assertEquals('Any', F\_stream_return_type_of_operation(F\_stream_operation(
		    'count', 'List ->Function -> Any'
		)));
	}

	public function test__stream_merge_types() {
		$this->assertEquals('Number', F\_stream_merge_types('Number', 'Number'));
		$this->assertEquals('Any', F\_stream_merge_types('Number', 'String'));
		$this->assertEquals('Any', F\_stream_merge_types('Any', 'String'));
	}

	public function test__stream_resolve() {
		$operations = [
		    F\_stream_operation('length', 'List|Array -> Number', 'count'),
		    F\_stream_operation('length', 'String -> Number', 'strlen'),
		    F\_stream_operation('map', 'Function -> List -> List', F\map()),
		    F\_stream_operation('reduce', 'Function -> Any -> List -> Any', F\reduce()),
		    F\_stream_operation('increment', 'Number -> Number', F\plus(1)),
		    F\_stream_operation('upperCase', 'String -> String', F\upperCase()),
		    F\_stream_operation('toString', 'Any -> String', F\toString()),
		    F\_stream_operation('head', 'List -> Any', F\head())
		];
		$stream = F\_stream($operations, [1, 2, 3]);
		$stream = F\_stream_apply_operation('length', [], $stream);
		$stream = F\_stream_resolve($stream);
		$this->assertEquals(true, F\get('resolved', $stream));
		$this->assertEquals(3, F\get('result', $stream));
		$stream = F\_stream($operations, [1, 2, 3]);
		$stream = F\_stream_apply_operation('map', [F\plus(2)], $stream); // [3, 4, 5]
		$stream = F\_stream_apply_operation('reduce', [F\plus(), 0], $stream); // 12
		$stream = F\_stream_apply_operation('increment', [], $stream); // 13
		$stream = F\_stream_resolve($stream);
		$this->assertEquals(true, F\get('resolved', $stream));
		$this->assertEquals(13, F\get('result', $stream));
		$stream = F\_stream($operations, []);
		$stream = F\_stream_apply_operation('head', [], $stream); // null
		$stream = F\_stream_apply_operation('increment', [], $stream); // Error
		$stream = F\_stream_apply_operation('toString', [], $stream); // Error
		$this->assertErrorThrown(function() use($stream) {
			$stream = F\_stream_resolve( $stream); 
		},
		"Stream: operation 'increment' could not be called with arguments types (Null); expected types are (Number)");
	}

}

