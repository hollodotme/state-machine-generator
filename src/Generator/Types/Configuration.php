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
	private $fqcn;

	public function __construct( string $for, string $fqcn )
	{
		$this->for  = $for;
		$this->fqcn = $fqcn;
	}

	public function getFor() : string
	{
		return $this->for;
	}

	public function getFullQualifiedClassName() : string
	{
		return ltrim( $this->fqcn, '\\' );
	}

	public function getClassName() : string
	{
		$parts = explode( '\\', $this->fqcn );

		return end( $parts );
	}

	public function getNamespace() : string
	{
		return join( '\\', array_filter( array_slice( explode( '\\', $this->fqcn ), 0, -1 ) ) );
	}
}
