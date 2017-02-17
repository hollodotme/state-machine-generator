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
 * Class AbstractStateClassGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class AbstractStateClassGenerator extends AbstractGenerator
{
	public function generate() : OutputFile
	{
		$spec                       = $this->getSpecification();
		$methodContents             = '';
		$authorsContent             = join( ', ', $spec->getAuthors() );
		$methodTemplate             = file_get_contents(
			(new TemplateFile( Template::ABSTRACT_STATE_CLASS_METHOD ))->toString()
		);
		$abstractStateClassConfig   = $spec->getConfiguration( Config::ABSTRACT_STATE_CLASS );
		$stateInterfaceConfig       = $spec->getConfiguration( Config::STATE_INTERFACE );
		$transitionExceptionConfig  = $spec->getConfiguration( Config::ILLEGAL_TRANSITION_EXCEPTION );
		$stateStringExceptionConfig = $spec->getConfiguration( Config::INVALID_STATE_STRING_EXCEPTION );
		$outputDir                  = $spec->getOutputSetting( 'stateClasses' )->getDir();
		$outputFilePath             = sprintf( '%s/%s.php', $outputDir, $abstractStateClassConfig->getClassName() );

		foreach ( $spec->getOperations() as $operation )
		{
			$methodContents .= str_replace(
				[
					'___METHOD___',
					'___STATE_INTERFACE___',
					'___ILLEGAL_TRANSITION_EXCEPTION___',
					'___DESIRED_STATE_CONSTANT___',
				],
				[
					$operation->getName(),
					$stateInterfaceConfig->getClassName(),
					$transitionExceptionConfig->getClassName(),
					$spec->getTargetStateOfOperation( $operation )->getConstantName(),
				],
				$methodTemplate . "\n"
			);
		}

		$stateCases     = [];
		$stateConstants = [];
		foreach ( $spec->getStates() as $state )
		{
			$stateConstants[] = sprintf(
				"const %s = '%s';\n",
				$state->getConstantName(),
				$state->getStringRepresentation()
			);

			$stateCases[] = str_replace(
				[
					'___STATE_STRING_CONSTANT___',
					'___STATE_CLASS_NAME___',
				],
				[
					$state->getConstantName(),
					$state->getName(),
				],
				file_get_contents( (new TemplateFile( Template::STATE_CASE ))->toString() )
			);
		}

		$content = str_replace(
			[
				'___AUTHORS___',
				'___NAMESPACE___',
				'___USE_STATE_INTERFACE___',
				'___USE_ILLEGAL_TRANSITION_EXCEPTION___',
				'___USE_INVALID_STATE_STRING_EXCEPTION___',
				'___CLASS_NAME___',
				'___STATE_INTERFACE___',
				'___METHODS___',
				'___STATE_CASES___',
				'___INVALID_STATE_STRING_EXCEPTION___',
				'___STATE_STRING_CONSTANTS___',
			],
			[
				$authorsContent,
				$abstractStateClassConfig->getNamespace(),
				$stateInterfaceConfig->getFullQualifiedClassName(),
				$transitionExceptionConfig->getFullQualifiedClassName(),
				$stateStringExceptionConfig->getFullQualifiedClassName(),
				$abstractStateClassConfig->getClassName(),
				$stateInterfaceConfig->getClassName(),
				rtrim( $methodContents, "\n" ),
				join( "\n", $stateCases ),
				$stateStringExceptionConfig->getClassName(),
				rtrim( join( "\n\t", $stateConstants ), "\n" ),
			],
			file_get_contents( (new TemplateFile( Template::ABSTRACT_STATE_CLASS ))->toString() )
		);

		return new OutputFile( $outputFilePath, $content );
	}
}
