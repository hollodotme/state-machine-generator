<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Exceptions;

use hollodotme\StateMachineGenerator\Exceptions\LogicException;

/**
 * Class TransitionNotFound
 * @package hollodotme\StateMachineGenerator\Generator\Exceptions
 */
final class TransitionNotFound extends LogicException
{
	private $operation;

	public function getOperation()
	{
		return $this->operation;
	}

	public function withOperation( string $operation ) : TransitionNotFound
	{
		$this->operation = $operation;
		$this->message   = sprintf( 'Transition not found [operation: %s].', $operation );

		return $this;
	}
}
