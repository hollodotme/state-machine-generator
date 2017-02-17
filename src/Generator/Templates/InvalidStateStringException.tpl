<?php declare(strict_types=1);
/**
 * @author ___AUTHORS___
 */

namespace ___NAMESPACE___;

/**
 * Class ___CLASS_NAME___
 * @package ___NAMESPACE___
 */
class ___CLASS_NAME___ extends \LogicException
{
	/** @var string */
	private $stateString;

	public function getStateString() : string
	{
		return $this->stateString;
	}

	public function withStateString( string $stateString ) : ___CLASS_NAME___
	{
		$this->stateString = $stateString;
		$this->message      = sprintf('Invalid state string [stateString: %s].', $stateString);

		return $this;
	}
}
