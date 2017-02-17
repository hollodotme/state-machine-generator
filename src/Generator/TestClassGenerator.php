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
 * Class TestClassGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class TestClassGenerator extends AbstractGenerator
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
		$mainClassConfig          = $spec->getConfiguration( Config::MAIN_CLASS );
		$abstractStateClassConfig = $spec->getConfiguration( Config::ABSTRACT_STATE_CLASS );
		$illegalTransitionConfig  = $spec->getConfiguration( Config::ILLEGAL_TRANSITION_EXCEPTION );
		$invalidStateStringConfig = $spec->getConfiguration( Config::INVALID_STATE_STRING_EXCEPTION );
		$testClassesConfig        = $spec->getConfiguration( Config::TEST_CLASSES );
		$testClassName            = $this->state->getName() . 'Test';
		$outputDir                = $spec->getOutputSetting( 'testClasses' )->getDir();
		$outputFilePath           = sprintf( '%s/%s.php', $outputDir, $testClassName );

		$queryMethodTemplate = file_get_contents( (new TemplateFile( Template::TEST_METHOD_QUERY ))->toString() );

		foreach ( $spec->getQueries() as $query )
		{
			if ( $query == $this->state->getQuery() )
			{
				$assert = 'True';
				$test   = ucfirst( $query );
			}
			else
			{
				$assert = 'False';
				$test   = str_replace( 'Is', 'IsNot', ucfirst( $query ) );
			}

			$methodsContent .= str_replace(
				[
					'___MAIN_CLASS_FQCN___',
					'___OBJECT___',
					'___TEST___',
					'___ASSERT___',
					'___QUERY___',
				],
				[
					$mainClassConfig->getFullQualifiedClassName(),
					lcfirst( $mainClassConfig->getClassName() ),
					$test,
					$assert,
					$query,
				],
				$queryMethodTemplate . "\n"
			);
		}

		$operationTemplate = file_get_contents(
			(new TemplateFile( Template::TEST_METHOD_OPERATION ))->toString()
		);

		$operationExceptionTemplate = file_get_contents(
			(new TemplateFile( Template::TEST_METHOD_OPERATION_EXCEPTION ))->toString()
		);

		foreach ( $spec->getOperations() as $operation )
		{
			if ( $this->state->hasTransitionForOperation( $operation ) )
			{
				$opTemplate = $operationTemplate;
				$test       = ucfirst( $operation->getAllowed() );
				$query      = $spec->getTargetStateOfOperation( $operation )->getQuery();
			}
			else
			{
				$opTemplate = $operationExceptionTemplate;
				$test       = ucfirst( $operation->getDisallowed() );
				$query      = '';
			}

			$methodsContent .= str_replace(
				[
					'___MAIN_CLASS_FQCN___',
					'___STATE_CLASS_FQCN___',
					'___OBJECT___',
					'___TEST___',
					'___OPERATION___',
					'___QUERY___',
					'___ILLEGAL_TRANSITION_EXCEPTION_FQCN___',
					'___DESIRED_STATE___',
					'___ILLEGAL_TRANSITION_EXCEPTION_NAME___',
				],
				[
					$mainClassConfig->getFullQualifiedClassName(),
					$abstractStateClassConfig->getNamespace() . '\\' . $this->state->getName(),
					lcfirst( $mainClassConfig->getClassName() ),
					$test,
					$operation->getName(),
					$query,
					$illegalTransitionConfig->getFullQualifiedClassName(),
					$spec->getTargetStateOfOperation( $operation )->getStringRepresentation(),
					$illegalTransitionConfig->getClassName(),
				],
				$opTemplate . "\n"
			);
		}

		$toStringTemplate = file_get_contents( (new TemplateFile( Template::TEST_METHOD_TO_STRING ))->toString() );

		$methodsContent .= str_replace(
			[
				'___STATE_CLASS_NAME___',
				'___STATE_CLASS_FQCN___',
				'___STATE_STRING_REPRESENTATION___',
			],
			[
				$this->state->getName(),
				$abstractStateClassConfig->getNamespace() . '\\' . $this->state->getName(),
				$this->state->getStringRepresentation(),
			],
			$toStringTemplate . "\n"
		);

		$fromStringTemplate = file_get_contents( (new TemplateFile( Template::TEST_METHOD_FROM_STRING ))->toString() );

		$methodsContent .= str_replace(
			[
				'___STATE_CLASS_NAME___',
				'___STATE_CLASS_FQCN___',
				'___STATE_STRING_REPRESENTATION___',
				'___ABSTRACT_STATE_CLASS_NAME___',
				'___ABSTRACT_STATE_CLASS_FQCN___',
				'___INVALID_STATE_STRING_EXCEPTION_FQCN___',
				'___INVALID_STATE_STRING_EXCEPTION_NAME___',
			],
			[
				$this->state->getName(),
				$abstractStateClassConfig->getNamespace() . '\\' . $this->state->getName(),
				$this->state->getStringRepresentation(),
				$abstractStateClassConfig->getClassName(),
				$abstractStateClassConfig->getFullQualifiedClassName(),
				$invalidStateStringConfig->getFullQualifiedClassName(),
				$invalidStateStringConfig->getClassName(),
			],
			$fromStringTemplate . "\n"
		);

		$content = str_replace(
			[
				'___AUTHORS___',
				'___NAMESPACE___',
				'___TEST_CLASS_NAME___',
				'___USE_STATE_CLASS___',
				'___USE_MAIN_CLASS___',
				'___USE_ABSTRACT_STATE_CLASS___',
				'___USE_ILLEGAL_TRANSITION_EXCEPTION___',
				'___USE_INVALID_STATE__STRING_EXCEPTION___',
				'___MAIN_CLASS_NAME___',
				'___STATE_CLASS_NAME___',
				'___OBJECT___',
				'___METHODS___',
			],
			[
				$authorsContent,
				$testClassesConfig->getNamespace(),
				$testClassName,
				$abstractStateClassConfig->getNamespace() . '\\' . $this->state->getName(),
				$mainClassConfig->getFullQualifiedClassName(),
				$abstractStateClassConfig->getFullQualifiedClassName(),
				$illegalTransitionConfig->getFullQualifiedClassName(),
				$invalidStateStringConfig->getFullQualifiedClassName(),
				$mainClassConfig->getClassName(),
				$this->state->getName(),
				lcfirst( $mainClassConfig->getClassName() ),
				rtrim( $methodsContent, "\n" ),
			],
			file_get_contents( (new TemplateFile( Template::TEST_CLASS ))->toString() )
		);

		return new OutputFile( $outputFilePath, $content );
	}
}
