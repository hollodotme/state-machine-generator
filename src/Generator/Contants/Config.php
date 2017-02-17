<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Contants;

/**
 * Class Config
 * @package hollodotme\StateMachineGenerator\Generator\Contants
 */
abstract class Config
{
	public const MAIN_CLASS                     = 'mainClass';

	public const ABSTRACT_STATE_CLASS           = 'abstractStateClass';

	public const STATE_INTERFACE                = 'stateInterface';

	public const ILLEGAL_TRANSITION_EXCEPTION   = 'illegalTransitionException';

	public const INVALID_STATE_STRING_EXCEPTION = 'invalidStateStringException';
}
