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

	/** @var array|Transition[] */
	private $transitions;

	public function __construct( string $name, string $query )
	{
		$this->name        = $name;
		$this->query       = $query;
		$this->transitions = [];
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getQuery() : string
	{
		return $this->query;
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
}
