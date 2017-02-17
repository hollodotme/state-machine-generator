<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\Generator\Contants;

/**
 * Class Template
 * @package hollodotme\StateMachineGenerator\Generator\Contants
 */
final class Template
{
	public const ABSTRACT_STATE_CLASS           = 'AbstractStateClass';

	public const ABSTRACT_STATE_CLASS_METHOD    = 'AbstractStateClassMethod';

	public const STATE_INTERFACE                = 'StateInterface';

	public const STATE_INTERFACE_METHOD         = 'StateInterfaceMethod';

	public const STATE_CLASS                    = 'StateClass';

	public const STATE_CLASS_METHOD             = 'StateClassMethod';

	public const MAIN_CLASS_OPERATION           = 'MainClassOperation';

	public const MAIN_CLASS_QUERY               = 'MainClassQuery';

	public const MAIN_CLASS                     = 'MainClass';

	public const ILLEGAL_TRANSITION_EXCEPTION   = 'IllegalStateTransitionException';

	public const INVALID_STATE_STRING_EXCEPTION = 'InvalidStateStringException';

	public const STATE_CASE                     = 'StateCase';
}
