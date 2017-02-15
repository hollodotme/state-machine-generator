<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Types;

/**
 * Class CodeFile
 * @package hollodotme\StateMachineGenerator\Generator\Types
 */
final class CodeFile
{
	/** @var string */
	private $name;

	public function __construct( string $name )
	{
		$this->name = $name;
	}

	public function __toString() : string
	{
		return __DIR__ . '/../../example/src/' . $this->name . '.php';
	}
}
