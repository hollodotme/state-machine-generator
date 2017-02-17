<?php declare(strict_types=1);
/**
 * @author ___AUTHORS___
 */

namespace ___NAMESPACE___;

use ___USE_STATE_INTERFACE___;
use ___USE_ILLEGAL_TRANSITION_EXCEPTION___;
___USE_STATE_CLASSES___

/**
 * Class ___CLASS_NAME___
 * @package ___NAMESPACE___
 */
class ___CLASS_NAME___
{
	/** @var ___STATE_INTERFACE___ */
	private $state;

	public function __construct(___STATE_INTERFACE___ $state)
	{
		$this->setState($state);
	}

	private function setState(___STATE_INTERFACE___ $state)
	{
		$this->state = $state;
	}

___OPERATIONS___

___QUERIES___
}
