<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Exceptions;

use hollodotme\StateMachineGenerator\Exceptions\RuntimeException;

/**
 * Class OutputSettingNotFound
 * @package hollodotme\StateMachineGenerator\Generator\Exceptions
 */
final class OutputSettingNotFound extends RuntimeException
{
	/** @var string */
	private $for;

	public function getFor() : string
	{
		return $this->for;
	}

	public function for ( string $for ) : OutputSettingNotFound
	{
		$this->for     = $for;
		$this->message = sprintf( 'Output setting not found [for: %s].', $for );

		return $this;
	}
}
