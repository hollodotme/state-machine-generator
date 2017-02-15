<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Types;

/**
 * Class Transition
 * @package hollodotme\StateMachineGenerator\Generator\Types
 */
final class Transition
{
	/** @var string */
	private $to;

	/** @var string */
	private $operation;

	public function __construct( string $to, string $operation )
	{
		$this->to        = $to;
		$this->operation = $operation;
	}

	public function to() : string
	{
		return $this->to;
	}

	public function getOperation() : string
	{
		return $this->operation;
	}
}
