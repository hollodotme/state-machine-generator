<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator;

use hollodotme\StateMachineGenerator\Generator\Contants\Config;
use hollodotme\StateMachineGenerator\Generator\Contants\Template;
use hollodotme\StateMachineGenerator\Generator\Types\OutputFile;
use hollodotme\StateMachineGenerator\Generator\Types\TemplateFile;

/**
 * Class StateInterfaceGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class StateInterfaceGenerator extends AbstractGenerator
{
	public function generate() : OutputFile
	{
		$spec = $this->getSpecification();

		$methodContents       = '';
		$authorsContent       = join( ', ', $spec->getAuthors() );
		$methodTemplate       = file_get_contents( (new TemplateFile( Template::STATE_INTERFACE_METHOD ))->toString() );
		$stateInterfaceConfig = $spec->getConfiguration( Config::STATE_INTERFACE );
		$outputDir            = $spec->getOutputSetting( 'stateInterface' )->getDir();
		$outputFilePath       = $outputDir . DIRECTORY_SEPARATOR . $stateInterfaceConfig->getClassName();

		foreach ( $spec->getOperations() as $operation )
		{
			$methodContents .= str_replace(
				[
					'___METHOD___',
					'___INTERFACE_NAME___',
				],
				[
					$operation->getName(),
					$stateInterfaceConfig->getClassName(),
				],
				$methodTemplate . "\n"
			);
		}

		$content = str_replace(
			[
				'___AUTHORS___',
				'___NAMESPACE___',
				'___INTERFACE_NAME___',
				'___METHODS___',
			],
			[
				$authorsContent,
				$stateInterfaceConfig->getNamespace(),
				$stateInterfaceConfig->getClassName(),
				rtrim( $methodContents, "\n" ),
			],
			file_get_contents( (new TemplateFile( Template::STATE_INTERFACE ))->toString() )
		);

		return new OutputFile( $outputFilePath, $content );
	}
}
