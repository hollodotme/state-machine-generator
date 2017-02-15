<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Types;

/**
 * Class Configuration
 * @package hollodotme\StateMachineGenerator\Generator\Types
 */
final class Configuration
{
	/** @var string */
	private $for;

	/** @var string */
	private $name;

	/** @var bool */
	private $isFinal;

	/** @var bool */
	private $isAbstract;

	public function __construct( string $for, string $name, bool $isFinal, bool $isAbstract )
	{
		$this->for        = $for;
		$this->name       = $name;
		$this->isFinal    = $isFinal;
		$this->isAbstract = $isAbstract;
	}

	public function getFor() : string
	{
		return $this->for;
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function isIsFinal() : bool
	{
		return $this->isFinal;
	}

	public function isIsAbstract() : bool
	{
		return $this->isAbstract;
	}
}
