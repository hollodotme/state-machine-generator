<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator;

use hollodotme\StateMachineGenerator\Generator\Exceptions\ConfigurationNotFound;
use hollodotme\StateMachineGenerator\Generator\Exceptions\OutputSettingNotFound;
use hollodotme\StateMachineGenerator\Generator\Exceptions\StateNotFound;
use hollodotme\StateMachineGenerator\Generator\Exceptions\TransitionNotFound;
use hollodotme\StateMachineGenerator\Generator\Types\Author;
use hollodotme\StateMachineGenerator\Generator\Types\Configuration;
use hollodotme\StateMachineGenerator\Generator\Types\Operation;
use hollodotme\StateMachineGenerator\Generator\Types\OutputSetting;
use hollodotme\StateMachineGenerator\Generator\Types\SpecificationFile;
use hollodotme\StateMachineGenerator\Generator\Types\State;
use hollodotme\StateMachineGenerator\Generator\Types\Transition;

/**
 * Class Specification
 * @package hollodotme\StateMachineGenerator\Generator
 */
final class Specification
{
	/** @var \SimpleXMLElement */
	private $xml;

	/** @var string */
	private $specFileDir;

	public function __construct( SpecificationFile $specificationFile )
	{
		$this->xml         = simplexml_load_file( $specificationFile->toString() );
		$this->specFileDir = dirname( $specificationFile->toString() );
	}

	public function getOutputSetting( string $for ) : OutputSetting
	{
		$outputSettings = $this->getOutputSettings();

		if ( isset( $outputSettings[ $for ] ) )
		{
			return $outputSettings[ $for ];
		}

		throw (new OutputSettingNotFound())->for( $for );
	}

	/**
	 * @return array|OutputSetting[]
	 */
	public function getOutputSettings() : array
	{
		$outputSettings = [];
		$paths          = $this->xml->xpath( '/specification/output/path' );

		foreach ( $paths as $path )
		{
			$for = (string)$path->attributes()['for'];

			$outputSettings[ $for ] = new OutputSetting(
				$for,
				$this->specFileDir . DIRECTORY_SEPARATOR . (string)$path->attributes()['dir']
			);
		}

		return $outputSettings;
	}

	public function getConfiguration( string $for ) : Configuration
	{
		$configurations = $this->getConfigurations();

		if ( isset( $configurations[ $for ] ) )
		{
			return $configurations[ $for ];
		}

		throw (new ConfigurationNotFound())->for( $for );
	}

	/**
	 * @return array|Configuration[]
	 */
	public function getConfigurations() : array
	{
		$configurations = [];
		$configs        = $this->xml->xpath( '/specification/configuration/config' );

		foreach ( $configs as $config )
		{
			$for = (string)$config->attributes()['for'];

			$configurations[ $for ] = new Configuration( $for, (string)$config->attributes()['fqcn'] );
		}

		return $configurations;
	}

	public function getQueries() : array
	{
		return array_column( $this->getStates(), 'query' );
	}

	/**
	 * @return array|Operation[]
	 */
	public function getOperations() : array
	{
		$operations = [];
		$ops        = $this->xml->xpath( '/specification/operations/operation' );

		foreach ( $ops as $operation )
		{
			$name                = (string)$operation->attributes()['name'];
			$operations[ $name ] = new Operation(
				$name,
				(string)$operation->attributes()['allowed'],
				(string)$operation->attributes()['disallowed']
			);
		}

		return $operations;
	}

	public function getState( $stateName ) : State
	{
		$states = $this->getStates();

		if ( isset( $states[ $stateName ] ) )
		{
			return $states[ $stateName ];
		}

		throw (new StateNotFound())->withStateName( $stateName );
	}

	/**
	 * @return array|State[]
	 */
	public function getStates() : array
	{
		$states   = [];
		$elements = $this->xml->xpath( '/specification/states/state' );

		foreach ( $elements as $stateElement )
		{
			$name            = (string)$stateElement->attributes()['name'];
			$states[ $name ] = new State(
				$name,
				(string)$stateElement->attributes()['query'],
				(string)$stateElement->attributes()['stringRepresentation']
			);
		}

		$transitions = $this->xml->xpath( '/specification/transitions/transition' );

		foreach ( $transitions as $transition )
		{
			$from = (string)$transition->attributes()['from'];

			$states[ $from ]->addTransition(
				new Transition(
					(string)$transition->attributes()['to'],
					(string)$transition->attributes()['operation']
				)
			);
		}

		return $states;
	}

	public function getAuthors() : array
	{
		$authors = [];

		$authorElements = $this->xml->xpath( '/specification/meta/author' );

		foreach ( $authorElements as $authorElement )
		{
			$authors[] = new Author(
				(string)$authorElement->attributes()['name'],
				(string)$authorElement->attributes()['email']
			);
		}

		return $authors;
	}

	public function getTargetStateOfOperation( Operation $operation ) : State
	{
		$xpath       = sprintf( '/specification/transitions/transition[@operation="%s"]', $operation->getName() );
		$transitions = $this->xml->xpath( $xpath );

		if ( !isset( $transitions[0] ) )
		{
			throw (new TransitionNotFound())->withOperation( $operation->getName() );
		}

		$targetState = (string)$transitions[0]->attributes()['to'];
		$xpath       = sprintf( '/specification/states/state[@name="%s"]', $targetState );
		$states      = $this->xml->xpath( $xpath );

		if ( !isset( $states[0] ) )
		{
			throw (new StateNotFound())->withStateName( $targetState );
		}

		return new State(
			(string)$states[0]->attributes()['name'],
			(string)$states[0]->attributes()['query'],
			(string)$states[0]->attributes()['stringRepresentation']
		);
	}
}
