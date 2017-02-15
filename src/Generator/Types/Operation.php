<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Types;

/**
 * Class Operation
 * @package hollodotme\StateMachineGenerator\Generator\Types
 */
final class Operation
{
	/** @var string */
	private $name;

	/** @var string */
	private $allowed;

	/** @var string */
	private $disallowed;

	public function __construct( string $name, string $allowed, string $disallowed )
	{
		$this->name       = $name;
		$this->allowed    = $allowed;
		$this->disallowed = $disallowed;
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getAllowed() : string
	{
		return $this->allowed;
	}

	public function getDisallowed() : string
	{
		return $this->disallowed;
	}
}
