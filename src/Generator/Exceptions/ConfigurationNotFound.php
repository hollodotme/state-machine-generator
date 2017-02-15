<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Exceptions;

use hollodotme\StateMachineGenerator\Exceptions\RuntimeException;

/**
 * Class ConfigurationNotFound
 * @package hollodotme\StateMachineGenerator\Generator\Exceptions
 */
final class ConfigurationNotFound extends RuntimeException
{
	/** @var string */
	private $for;

	public function getFor() : string
	{
		return $this->for;
	}

	public function for ( string $for ) : ConfigurationNotFound
	{
		$this->for     = $for;
		$this->message = sprintf( 'Configuration not found [for: %s].', $for );

		return $this;
	}
}
