<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator;

use hollodotme\StateMachineGenerator\Generator\Types\CodeFile;
use hollodotme\StateMachineGenerator\Generator\Types\TemplateFile;

/**
 * Class StateClassGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class StateClassGenerator
{
	public function generate( array $data, string $className, string $abstractClassName )
	{
		$buffer   = '';
		$template = file_get_contents( new TemplateFile( 'StateClassMethod' ) );

		foreach ( $data['transitions'] as $operation => $to )
		{
			$buffer .= str_replace(
				[
					'___STATE___',
					'___METHOD___',
				],
				[
					$to,
					$operation,
				],
				$template
			);
		}

		file_put_contents(
			new CodeFile( $className ),
			str_replace(
				[
					'___CLASS___',
					'___ABSTRACT___',
					'___METHODS___',
				],
				[
					$className,
					$abstractClassName,
					$buffer,
				],
				file_get_contents( new TemplateFile( 'StateClass' ) )
			)
		);
	}
}
