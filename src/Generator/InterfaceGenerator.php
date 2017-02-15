<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator;

use hollodotme\StateMachineGenerator\Generator\Types\CodeFile;
use hollodotme\StateMachineGenerator\Generator\Types\OutputSetting;
use hollodotme\StateMachineGenerator\Generator\Types\TemplateFile;

/**
 * Class InterfaceGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class InterfaceGenerator
{
	/** @var OutputSetting */
	private $outputSetting;

	public function __construct( OutputSetting $outputSetting )
	{
		$this->outputSetting = $outputSetting;
	}

	public function generate( array $operations, string $interfaceName )
	{
		$buffer   = '';
		$template = file_get_contents( new TemplateFile( 'InterfaceMethod' ) );

		foreach ( array_keys( $operations ) as $operation )
		{
			$buffer .= str_replace( '___METHOD___', $operation, $template );
		}

		file_put_contents(
			new CodeFile( $interfaceName ),
			str_replace(
				[
					'___INTERFACE___',
					'___METHODS___',
				],
				[
					$interfaceName,
					$buffer,
				],
				file_get_contents( new TemplateFile( 'Interface' ) )
			)
		);
	}
}
