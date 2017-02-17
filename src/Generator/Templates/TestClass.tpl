<?php declare(strict_types=1);
/**
 * @author ___AUTHORS___
 */

namespace ___NAMESPACE___;

use ___USE_MAIN_CLASS___;
use ___USE_ABSTRACT_STATE_CLASS___;
use ___USE_INVALID_STATE__STRING_EXCEPTION___;
use ___USE_ILLEGAL_TRANSITION_EXCEPTION___;
use ___USE_STATE_CLASS___;

/**
 * Class ___TEST_CLASS_NAME___
 * @package ___NAMESPACE___
 */
final class ___TEST_CLASS_NAME___ extends \PHPUnit\Framework\TestCase
{
    /** @var ___MAIN_CLASS_NAME___ */
    private $___OBJECT___;

    protected function setUp()
    {
        $this->___OBJECT___ = new ___MAIN_CLASS_NAME___(new ___STATE_CLASS_NAME___());
	}

___METHODS___
}
