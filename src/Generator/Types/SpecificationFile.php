<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Types;

/**
 * Class SpecificationFile
 * @package hollodotme\StateMachineGenerator\Generator\Types
 */
final class SpecificationFile
{
	/** @var string|null */
	private $filePath;

	public function __construct( string $filePath )
	{
		$this->filePath = $filePath;
	}

	public function exists() : bool
	{
		return file_exists( realpath( $this->filePath ) );
	}

	public function toString() : string
	{
		return realpath( $this->filePath );
	}
}
