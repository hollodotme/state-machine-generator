<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator;

use hollodotme\StateMachineGenerator\Generator\Types\OutputFile;

/**
 * Class AbstractGenerator
 * @package hollodotme\StateMachineGenerator\Generator
 */
abstract class AbstractGenerator
{
	/** @var Specification */
	private $specification;

	public function __construct( Specification $specification )
	{
		$this->specification = $specification;
	}

	final protected function getSpecification() : Specification
	{
		return $this->specification;
	}

	abstract public function generate() : OutputFile;
}
