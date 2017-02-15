<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Types;

/**
 * Class TestFile
 * @package hollodotme\StateMachineGenerator\Generator\Types
 */
final class TestFile
{
	/** @var string */
	private $name;

	public function __construct( string $testName )
	{
		$this->name = $testName;
	}

	public function __toString() : string
	{
		return __DIR__ . '/../../example/tests/' . $this->name . '.php';
	}
}
