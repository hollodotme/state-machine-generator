<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Types;

/**
 * Class OutputFile
 * @package hollodotme\StateMachineGenerator\Generator\Types
 */
final class OutputFile
{
	/** @var string */
	private $filePath;

	/** @var string */
	private $content;

	public function __construct( string $filePath, string $content )
	{
		$this->filePath = $filePath;
		$this->content  = $content;
	}

	public function getDir() : string
	{
		return dirname( $this->filePath );
	}

	public function getFilePath() : string
	{
		return $this->filePath;
	}

	public function getContent() : string
	{
		return $this->content;
	}
}
