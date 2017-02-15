<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Types;

/**
 * Class Author
 * @package hollodotme\StateMachineGenerator\Generator\Types
 */
final class Author
{
	/** @var string */
	private $name;

	/** @var string */
	private $email;

	public function __construct( string $name, string $email )
	{
		$this->name  = $name;
		$this->email = $email;
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getEmail() : string
	{
		return $this->email;
	}

	public function __toString() : string
	{
		if ( empty( $this->email ) )
		{
			return $this->name;
		}

		return sprintf( '%s <%s>', $this->name, $this->email );
	}
}
