<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator;

use hollodotme\StateMachineGenerator\Generator\Types\OutputSetting;
use hollodotme\StateMachineGenerator\Generator\Types\TemplateFile;
use hollodotme\StateMachineGenerator\Generator\Types\TestFile;

/**
 * Class TestGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class TestGenerator
{
	/** @var OutputSetting */
	private $outputSetting;

	public function __construct( OutputSetting $outputSetting )
	{
		$this->outputSetting = $outputSetting;
	}

	public function generate(
		array $data,
		array $operations,
		array $queries,
		array $states,
		string $state,
		string $className,
		string $abstractState
	)
	{
		$buffer        = '';
		$state         = substr( $state, 0, strlen( $state ) - strlen( 'State' ) );
		$abstractState = substr( $abstractState, 0, strlen( $abstractState ) - strlen( 'State' ) );
		$template      = file_get_contents( new TemplateFile( 'TestMethodQuery' ) );

		foreach ( $queries as $query )
		{
			if ( $query == $data['query'] )
			{
				$assert = 'True';
				$test   = ucfirst( $query );
			}
			else
			{
				$assert = 'False';
				$test   = str_replace( 'Is', 'IsNot', ucfirst( $query ) );
			}

			$buffer .= str_replace(
				[
					'___CLASS___',
					'___OBJECT___',
					'___TEST___',
					'___ASSERT___',
					'___QUERY___',
				],
				[
					$className,
					strtolower( $className ),
					$test,
					$assert,
					$query,
				],
				$template
			);
		}

		$operationTemplate          = file_get_contents( new TemplateFile( 'TestMethodOperation' ) );
		$operationExceptionTemplate = file_get_contents( new TemplateFile( 'TestMethodOperationException' ) );

		foreach ( $operations as $operation => $names )
		{
			if ( isset( $data['transitions'][ $operation ] ) )
			{
				$template = $operationTemplate;
				$test     = ucfirst( $names['allowed'] );
				$_state   = $state;
				$query    = $states[ $data['transitions'][ $operation ] ]['query'];
			}
			else
			{
				$template = $operationExceptionTemplate;
				$test     = ucfirst( $names['disallowed'] );
				$_state   = $abstractState;
				$query    = '';
			}

			$buffer .= str_replace(
				[
					'___CLASS___',
					'___OBJECT___',
					'___STATE___',
					'___TEST___',
					'___OPERATION___',
					'___QUERY___',
				],
				[
					$className,
					strtolower( $className ),
					$_state,
					$test,
					$operation,
					$query,
				],
				$template
			);
		}

		file_put_contents(
			new TestFile( $state . 'Test' ),
			str_replace(
				[
					'___STATE___',
					'___CLASS___',
					'___OBJECT___',
					'___METHODS___',
				],
				[
					$state,
					$className,
					strtolower( $className ),
					$buffer,
				],
				file_get_contents( new TemplateFile( 'TestClass' ) )
			)
		);
	}
}
