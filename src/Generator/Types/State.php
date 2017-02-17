<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Types;

/**
 * Class State
 * @package hollodotme\StateMachineGenerator\Generator\Types
 */
final class State
{
	/** @var string */
	private $name;

	/** @var string */
	public $query;

	/** @var string */
	private $stringRepresentation;

	/** @var array|Transition[] */
	private $transitions;

	public function __construct( string $name, string $query, string $stringRepresentation )
	{
		$this->name                 = $name;
		$this->query                = $query;
		$this->stringRepresentation = $stringRepresentation;
		$this->transitions          = [];
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getQuery() : string
	{
		return $this->query;
	}

	public function getStringRepresentation() : string
	{
		return $this->stringRepresentation;
	}

	public function getConstantName() : string
	{
		return strtoupper( preg_replace( [ "#[^a-z0-9_]#", "#_+#" ], '_', $this->stringRepresentation ) );
	}

	public function addTransition( Transition $transition )
	{
		$this->transitions[] = $transition;
	}

	/**
	 * @return array|Transition[]
	 */
	public function getTransitions() : array
	{
		return $this->transitions;
	}

	public function hasTransitionForOperation( Operation $operation ) : bool
	{
		foreach ( $this->transitions as $transition )
		{
			if ( $transition->getOperation() == $operation->getName() )
			{
				return true;
			}
		}

		return false;
	}
}
