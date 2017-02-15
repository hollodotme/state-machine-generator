<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Types;

/**
 * Class TemplateFile
 * @package hollodotme\StateMachineGenerator\Generator\Types
 */
final class TemplateFile
{
	/** @var string */
	private $name;

	public function __construct( string $name )
	{
		$this->name = $name;
	}

	public function toString() : string
	{
		return __DIR__ . '/../Templates/' . $this->name . '.tpl';
	}
}
