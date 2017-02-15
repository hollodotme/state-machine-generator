<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator;

use hollodotme\StateMachineGenerator\Generator\Types\CodeFile;
use hollodotme\StateMachineGenerator\Generator\Types\OutputSetting;
use hollodotme\StateMachineGenerator\Generator\Types\TemplateFile;

/**
 * Class AbstractStateClassGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class AbstractStateClassGenerator
{
	/** @var OutputSetting */
	private $outputSetting;

	public function __construct( OutputSetting $outputSetting )
	{
		$this->outputSetting = $outputSetting;
	}

	public function generate( array $operations, string $abstractClassName, string $interfaceName )
	{
		$buffer   = '';
		$template = file_get_contents( new TemplateFile( 'AbstractStateClassMethod' ) );

		foreach ( $operations as $operation => $data )
		{
			$buffer .= str_replace(
				'___METHOD___',
				$operation,
				$template
			);
		}

		file_put_contents(
			new CodeFile( $abstractClassName ),
			str_replace(
				[
					'___ABSTRACT___',
					'___INTERFACE___',
					'___METHODS___',
				],
				[
					$abstractClassName,
					$interfaceName,
					$buffer,
				],
				file_get_contents( new TemplateFile( 'AbstractStateClass' ) )
			)
		);
	}
}
