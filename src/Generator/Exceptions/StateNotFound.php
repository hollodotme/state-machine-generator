<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Exceptions;

use hollodotme\StateMachineGenerator\Exceptions\LogicException;

/**
 * Class StateNotFound
 * @package hollodotme\StateMachineGenerator\Generator\Exceptions
 */
final class StateNotFound extends LogicException
{
	/** @var string */
	private $stateName;

	public function getStateName() : string
	{
		return $this->stateName;
	}

	public function withStateName( string $stateName ) : StateNotFound
	{
		$this->stateName = $stateName;
		$this->message   = sprintf( 'State not found [stateName: %s].', $stateName );

		return $this;
	}
}
