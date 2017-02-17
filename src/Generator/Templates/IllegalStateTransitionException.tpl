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
	private $desiredState;

	public function getDesiredState() : string
	{
		return $this->desiredState;
	}

	public function withDesiredState( string $desiredState ) : ___CLASS_NAME___
	{
		$this->desiredState = $desiredState;
		$this->message      = sprintf('Illegal state transition [desiredState: %s].', $desiredState);

		return $this;
	}
}
