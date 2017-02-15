<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Types;

/**
 * Class OutputSetting
 * @package hollodotme\StateMachineGenerator\Generator\Types
 */
final class OutputSetting
{
	/** @var string */
	private $for;

	/** @var string */
	private $dir;

	public function __construct( string $for, string $dir )
	{
		$this->for = $for;
		$this->dir = $dir;
	}

	public function getFor() : string
	{
		return $this->for;
	}

	public function getDir() : string
	{
		return $this->dir;
	}
}
