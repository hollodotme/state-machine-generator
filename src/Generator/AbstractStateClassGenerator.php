<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator;

use hollodotme\StateMachineGenerator\Generator\Types\OutputFile;
use hollodotme\StateMachineGenerator\Generator\Types\TemplateFile;

/**
 * Class AbstractStateClassGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class AbstractStateClassGenerator extends AbstractGenerator
{
	public function generate() : OutputFile
	{
		$spec                      = $this->getSpecification();
		$methodContents            = '';
		$authorsContent            = join( ', ', $spec->getAuthors() );
		$methodTemplate            = file_get_contents( (new TemplateFile( 'AbstractStateClassMethod' ))->toString() );
		$abstractStateClassConfig  = $spec->getConfiguration( 'abstractStateClass' );
		$stateInterfaceConfig      = $spec->getConfiguration( 'stateInterface' );
		$transitionExceptionConfig = $spec->getConfiguration( 'illegalTransitionException' );
		$outputDir                 = $spec->getOutputSetting( 'stateClasses' )->getDir();
		$outputFilePath            = $outputDir . DIRECTORY_SEPARATOR . $abstractStateClassConfig->getClassName();

		foreach ( $spec->getOperations() as $operation )
		{
			$methodContents .= str_replace(
				[
					'___METHOD___',
					'___STATE_INTERFACE___',
					'___ILLEGAL_TRANSITION_EXCEPTION___',
				],
				[
					$operation->getName(),
					$stateInterfaceConfig->getClassName(),
					$transitionExceptionConfig->getClassName(),
				],
				$methodTemplate . "\n"
			);
		}

		$content = str_replace(
			[
				'___AUTHORS___',
				'___NAMESPACE___',
				'___USE_INTERFACE___',
				'___USE_ILLEGAL_TRANSITION_EXCEPTION___',
				'___CLASS_NAME___',
				'___INTERFACE___',
				'___METHODS___',
			],
			[
				$authorsContent,
				$abstractStateClassConfig->getNamespace(),
				$stateInterfaceConfig->getFullQualifiedClassName(),
				$transitionExceptionConfig->getFullQualifiedClassName(),
				$abstractStateClassConfig->getClassName(),
				$stateInterfaceConfig->getClassName(),
				rtrim( $methodContents, "\n" ),
			],
			file_get_contents( (new TemplateFile( 'AbstractStateClass' ))->toString() )
		);

		return new OutputFile( $outputFilePath, $content );
	}
}
