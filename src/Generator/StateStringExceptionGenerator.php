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
 * Class StateStringExceptionGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class StateStringExceptionGenerator extends AbstractGenerator
{
	public function generate() : OutputFile
	{
		$spec = $this->getSpecification();

		$authorsContent                    = join( ', ', $spec->getAuthors() );
		$invalidStateStringExceptionConfig = $spec->getConfiguration( Config::INVALID_STATE_STRING_EXCEPTION );

		$outputDir      = $spec->getOutputSetting( 'exceptions' )->getDir();
		$outputFilePath = sprintf( '%s/%s.php', $outputDir, $invalidStateStringExceptionConfig->getClassName() );

		$content = str_replace(
			[
				'___AUTHORS___',
				'___NAMESPACE___',
				'___CLASS_NAME___',
			],
			[
				$authorsContent,
				$invalidStateStringExceptionConfig->getNamespace(),
				$invalidStateStringExceptionConfig->getClassName(),

			],
			file_get_contents( (new TemplateFile( Template::INVALID_STATE_STRING_EXCEPTION ))->toString() )
		);

		return new OutputFile( $outputFilePath, $content );
	}
}
