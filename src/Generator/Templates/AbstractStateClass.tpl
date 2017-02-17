<?php declare(strict_types=1);
/**
 * @author ___AUTHORS___
 */

namespace ___NAMESPACE___;

use ___USE_STATE_INTERFACE___;
use ___USE_ILLEGAL_TRANSITION_EXCEPTION___;
use ___USE_INVALID_STATE_STRING_EXCEPTION___;

/**
 * Class ___CLASS_NAME___
 * @package ___NAMESPACE___
 */
abstract class ___CLASS_NAME___ implements ___STATE_INTERFACE___
{
	___STATE_STRING_CONSTANTS___

___METHODS___

	public function __toString() : string
	{
		return $this->toString();
	}

	/**
	 * @param string $stateString
	 * @throws ___INVALID_STATE_STRING_EXCEPTION___
	 * @return ___STATE_INTERFACE___
	 */
	public static function fromString(string $stateString) : ___STATE_INTERFACE___
	{
		switch($stateString)
		{
___STATE_CASES___
			default:
				throw (new ___INVALID_STATE_STRING_EXCEPTION___())->withStateString($stateString);
		}
	}
}
