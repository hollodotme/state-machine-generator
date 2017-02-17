<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator;

use hollodotme\StateMachineGenerator\Generator\Contants\Config;
use hollodotme\StateMachineGenerator\Generator\Contants\Template;
use hollodotme\StateMachineGenerator\Generator\Types\OutputFile;
use hollodotme\StateMachineGenerator\Generator\Types\State;
use hollodotme\StateMachineGenerator\Generator\Types\TemplateFile;

/**
 * Class StateClassGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class StateClassGenerator extends AbstractGenerator
{
	/** @var State */
	private $state;

	public function __construct( Specification $specification, State $state )
	{
		parent::__construct( $specification );
		$this->state = $state;
	}

	public function generate() : OutputFile
	{
		$spec = $this->getSpecification();

		$methodsContent           = '';
		$authorsContent           = join( ', ', $spec->getAuthors() );
		$stateInterfaceConfig     = $spec->getConfiguration( Config::STATE_INTERFACE );
		$abstractStateClassConfig = $spec->getConfiguration( Config::ABSTRACT_STATE_CLASS );
		$methodTemplate           = file_get_contents( (new TemplateFile( Template::STATE_CLASS_METHOD ))->toString() );
		$outputDir                = $spec->getOutputSetting( 'stateClasses' )->getDir();
		$outputFilePath           = sprintf( '%s/%s.php', $outputDir, $this->state->getName() );

		foreach ( $this->state->getTransitions() as $transition )
		{
			$methodsContent .= str_replace(
				[
					'___STATE___',
					'___METHOD___',
					'___INTERFACE_NAME___',
				],
				[
					$transition->to(),
					$transition->getOperation(),
					$stateInterfaceConfig->getClassName(),
				],
				$methodTemplate . "\n"
			);
		}

		$content = str_replace(
			[
				'___AUTHORS___',
				'___NAMESPACE___',
				'___USE_STATE_INTERFACE___',
				'___CLASS_NAME___',
				'___ABSTRACT_STATE_CLASS___',
				'___METHODS___',
			],
			[
				$authorsContent,
				$abstractStateClassConfig->getNamespace(),
				$stateInterfaceConfig->getFullQualifiedClassName(),
				$this->state->getName(),
				$abstractStateClassConfig->getClassName(),
				rtrim( $methodsContent, "\n" ),
			],
			file_get_contents( (new TemplateFile( Template::STATE_CLASS ))->toString() )
		);

		return new OutputFile( $outputFilePath, $content );
	}
}
