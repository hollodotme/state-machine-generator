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
 * Class MainClassGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class MainClassGenerator extends AbstractGenerator
{
	public function generate() : OutputFile
	{
		$spec = $this->getSpecification();

		$operationsContent                = '';
		$queriesContent                   = '';
		$authorsContent                   = join( ', ', $spec->getAuthors() );
		$mainClassConfig                  = $spec->getConfiguration( Config::MAIN_CLASS );
		$stateInterfaceConfig             = $spec->getConfiguration( Config::STATE_INTERFACE );
		$abstractStateClassConfig         = $spec->getConfiguration( Config::ABSTRACT_STATE_CLASS );
		$illegalTransitionExceptionConfig = $spec->getConfiguration( Config::ILLEGAL_TRANSITION_EXCEPTION );

		$outputDir      = $spec->getOutputSetting( 'mainClass' )->getDir();
		$outputFilePath = sprintf( '%s/%s.php', $outputDir, $mainClassConfig->getClassName() );

		$operationTemplate = file_get_contents( (new TemplateFile( Template::MAIN_CLASS_OPERATION ))->toString() );

		foreach ( $spec->getOperations() as $operation )
		{
			$operationsContent .= str_replace(
				[
					'___ILLEGAL_TRANSITION_EXCEPTION___',
					'___METHOD___',
				],
				[
					$illegalTransitionExceptionConfig->getClassName(),
					$operation->getName(),
				],
				$operationTemplate . "\n"
			);
		}

		$queryTemplate   = file_get_contents( (new TemplateFile( Template::MAIN_CLASS_QUERY ))->toString() );
		$useStateClasses = [];

		foreach ( $spec->getStates() as $state )
		{
			$useStateClasses[] = sprintf( 'use %s\\%s;', $abstractStateClassConfig->getNamespace(), $state->getName() );

			$queriesContent .= str_replace(
				[
					'___QUERY___',
					'___STATE___',
				],
				[
					$state->getQuery(),
					$state->getName(),
				],
				$queryTemplate . "\n"
			);
		}

		$content = str_replace(
			[
				'___AUTHORS___',
				'___NAMESPACE___',
				'___CLASS_NAME___',
				'___STATE_INTERFACE___',
				'___USE_STATE_INTERFACE___',
				'___USE_STATE_CLASSES___',
				'___USE_ILLEGAL_TRANSITION_EXCEPTION___',
				'___OPERATIONS___',
				'___QUERIES___',
			],
			[
				$authorsContent,
				$mainClassConfig->getNamespace(),
				$mainClassConfig->getClassName(),
				$stateInterfaceConfig->getClassName(),
				$stateInterfaceConfig->getFullQualifiedClassName(),
				join( "\n", $useStateClasses ),
				$illegalTransitionExceptionConfig->getFullQualifiedClassName(),
				rtrim( $operationsContent, "\n" ),
				rtrim( $queriesContent, "\n" ),
			],
			file_get_contents( (new TemplateFile( Template::MAIN_CLASS ))->toString() )
		);

		return new OutputFile( $outputFilePath, $content );
	}
}
