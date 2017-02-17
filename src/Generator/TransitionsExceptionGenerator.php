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
 * Class TransitionsExceptionGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class TransitionsExceptionGenerator extends AbstractGenerator
{
	public function generate() : OutputFile
	{
		$spec = $this->getSpecification();

		$authorsContent                   = join( ', ', $spec->getAuthors() );
		$illegalTransitionExceptionConfig = $spec->getConfiguration( Config::ILLEGAL_TRANSITION_EXCEPTION );

		$outputDir      = $spec->getOutputSetting( 'exceptions' )->getDir();
		$outputFilePath = sprintf( '%s/%s.php', $outputDir, $illegalTransitionExceptionConfig->getClassName() );

		$content = str_replace(
			[
				'___AUTHORS___',
				'___NAMESPACE___',
				'___CLASS_NAME___',
			],
			[
				$authorsContent,
				$illegalTransitionExceptionConfig->getNamespace(),
				$illegalTransitionExceptionConfig->getClassName(),

			],
			file_get_contents( (new TemplateFile( Template::ILLEGAL_TRANSITION_EXCEPTION ))->toString() )
		);

		return new OutputFile( $outputFilePath, $content );
	}
}
