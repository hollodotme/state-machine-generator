<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator;

use hollodotme\StateMachineGenerator\Generator\Types\CodeFile;
use hollodotme\StateMachineGenerator\Generator\Types\OutputSetting;
use hollodotme\StateMachineGenerator\Generator\Types\TemplateFile;

/**
 * Class MainClassGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class MainClassGenerator
{
	/** @var OutputSetting */
	private $outputSetting;

	public function __construct( OutputSetting $outputSetting )
	{
		$this->outputSetting = $outputSetting;
	}

	public function generate( array $operations, array $states, string $className, string $interfaceName )
	{
		$buffer   = '';
		$template = file_get_contents( new TemplateFile( 'ClassOperation' ) );

		foreach ( array_keys( $operations ) as $operation )
		{
			$buffer .= str_replace( '___METHOD___', $operation, $template );
		}

		$template = file_get_contents( new TemplateFile( 'ClassQuery' ) );

		foreach ( $states as $state => $data )
		{
			$buffer .= str_replace(
				[
					'___METHOD___',
					'___STATE___',
				],
				[
					$data['query'],
					$state,
				],
				$template
			);
		}

		file_put_contents(
			new CodeFile( $className ),
			str_replace(
				[
					'___CLASS___',
					'___INTERFACE___',
					'___METHODS___',
				],
				[
					$className,
					$interfaceName,
					$buffer,
				],
				file_get_contents( new TemplateFile( 'Class' ) )
			)
		);
	}
}
