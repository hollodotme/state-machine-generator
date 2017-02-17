[![Build Status](https://travis-ci.org/hollodotme/state-machine-generator.svg?branch=master)](https://travis-ci.org/hollodotme/state-machine-generator)
[![Coverage Status](https://coveralls.io/repos/github/hollodotme/state-machine-generator/badge.svg?branch=master)](https://coveralls.io/github/hollodotme/state-machine-generator?branch=master)

# State machine generator

A PHP code generator for OOP state machines.

This package is based on the work of [Sebastian Bergmann and his state repository](https://github.com/sebastianbergmann/state).

## Requirements

* PHP >= 7.1
* PHPUnit >= 6.0 (for running the generated tests)

## Features

* Command based CLI API
* Support for namespaces
* States can be represented as and reconstituted from strings
* Configurable output paths
* Configurable meta data
* Template specification file creation
* Generator available as a PHAR file

## Installation

Download the current stable PHAR file from the [releases section](https://github.com/hollodotme/state-machine-generator/releases/).

	https://github.com/hollodotme/state-machine-generator/releases/download/vX.X.X/state-machine-generator.phar
	
If you like verify it with GPG:

	https://github.com/hollodotme/state-machine-generator/releases/download/vX.X.X/state-machine-generator.phar.asc

## Usage

### Create a sample specification file

```bash
$ php state-machine-generator.phar create:specfile /path/to/specification-file.xml
```

### Generate state machine classes incl. exceptions and unit tests

```bash
$ php state-machine-generator.phar generate:machine /path/to/specification-file.xml
```

## Example

### Specification file

```xml
<?xml version="1.0" encoding="UTF-8"?>
<specification>
	<meta>
		<!-- NOTE: You can add multiple author nodes. -->
		<author name="Your Name" email="you@example.com"/>
	</meta>
	<output>
		<!-- NOTE: Paths must be relative to the directory of this file. -->
		<path for="mainClass" dir="src"/>
		<path for="stateInterface" dir="src/States/Interfaces"/>
		<path for="stateClasses" dir="src/States"/>
		<path for="testClasses" dir="tests/Unit"/>
		<path for="exceptions" dir="src/States/Exceptions"/>
	</output>
	<configuration>
		<!-- NOTE: The value in "fqcn" attribute must be the full qualified class/interface name. -->
		<config for="mainClass" fqcn="YourVendor\YourProject\Door"/>
		<config for="stateInterface" fqcn="YourVendor\YourProject\States\Interfaces\DoorState"/>
		<config for="abstractStateClass" fqcn="YourVendor\YourProject\States\AbstractDoorState"/>
		<config for="illegalTransitionException" fqcn="YourVendor\YourProject\States\Exceptions\IllegalStateTransition"/>
		<config for="invalidStateStringException" fqcn="YourVendor\YourProject\States\Exceptions\InvalidStateString"/>
		<!-- NOTE: Leave the "*" as the last namespace component -->
		<config for="testClasses" fqcn="YourVendor\YourProject\Tests\Unit\*"/>
	</configuration>
	<states>
		<state name="OpenDoorState" query="isOpen" stringRepresentation="open"/>
		<state name="ClosedDoorState" query="isClosed" stringRepresentation="closed"/>
		<state name="LockedDoorState" query="isLocked" stringRepresentation="locked"/>
	</states>
	<transitions>
		<transition from="ClosedDoorState" to="OpenDoorState" operation="open"/>
		<transition from="OpenDoorState" to="ClosedDoorState" operation="close"/>
		<transition from="ClosedDoorState" to="LockedDoorState" operation="lock"/>
		<transition from="LockedDoorState" to="ClosedDoorState" operation="unlock"/>
	</transitions>
	<operations>
		<operation name="open" allowed="canBeOpened" disallowed="cannotBeOpened"/>
		<operation name="close" allowed="canBeClosed" disallowed="cannotBeClosed"/>
		<operation name="lock" allowed="canBeLocked" disallowed="cannotBeLocked"/>
		<operation name="unlock" allowed="canBeUnlocked" disallowed="cannotBeUnlocked"/>
	</operations>
</specification>

```

### Generated files

```
- /src
   |- /States
   |   |- /Exceptions
   |   |   |- IllegalStateTransition
   |   |   `- InvalidStateString
   |   |- /Interfaces
   |   |   `- DoorState.php
   |   |- AbstractDoorState.php
   |   |- ClosedDoorState.php
   |   |- LockedDoorState.php
   |   `- OpenDoorState.php
   `- Door.php
- /tests
   |- ClosedDoorStateTest.php
   |- LockedDoorStateTest.php
   `- OpenDoorStateTest.php
```

#### Interface `DoorState`

```php
<?php declare(strict_types=1);
/**
 * @author Your Name <you@example.com>
 */

namespace YourVendor\YourProject\States\Interfaces;

/**
 * Interface DoorState
 * @package YourVendor\YourProject\States\Interfaces
 */
interface DoorState
{
	public function open() : DoorState;

	public function close() : DoorState;

	public function lock() : DoorState;

	public function unlock() : DoorState;

	public function toString() : string;

	public function __toString() : string;
}
```

### Class `AbstractDoorState`

```php
<?php declare(strict_types=1);
/**
 * @author Your Name <you@example.com>
 */

namespace YourVendor\YourProject\States;

use YourVendor\YourProject\States\Interfaces\DoorState;
use YourVendor\YourProject\States\Exceptions\IllegalStateTransition;
use YourVendor\YourProject\States\Exceptions\InvalidStateString;

/**
 * Class AbstractDoorState
 * @package YourVendor\YourProject\States
 */
abstract class AbstractDoorState implements DoorState
{
	protected const OPEN = 'open';

	protected const CLOSED = 'closed';

	protected const LOCKED = 'locked';

	public function open() : DoorState
	{
		throw (new IllegalStateTransition())->withDesiredState(self::OPEN);
	}

	public function close() : DoorState
	{
		throw (new IllegalStateTransition())->withDesiredState(self::CLOSED);
	}

	public function lock() : DoorState
	{
		throw (new IllegalStateTransition())->withDesiredState(self::LOCKED);
	}

	public function unlock() : DoorState
	{
		throw (new IllegalStateTransition())->withDesiredState(self::CLOSED);
	}

	public function __toString() : string
	{
		return $this->toString();
	}

	/**
	 * @param string $stateString
	 * @throws InvalidStateString
	 * @return DoorState
	 */
	public static function fromString(string $stateString) : DoorState
	{
		switch($stateString)
		{
			case self::OPEN:
				return new OpenDoorState();

			case self::CLOSED:
				return new ClosedDoorState();

			case self::LOCKED:
				return new LockedDoorState();

			default:
				throw (new InvalidStateString())->withStateString($stateString);
		}
	}
}
```

#### Class `ClosedDoorState`

```php
<?php declare(strict_types=1);
/**
 * @author Your Name <you@example.com>
 */

namespace YourVendor\YourProject\States;

use YourVendor\YourProject\States\Interfaces\DoorState;

/**
 * Class ClosedDoorState
 * @package YourVendor\YourProject\States
 */
final class ClosedDoorState extends AbstractDoorState
{
	public function open() : DoorState
	{
		return new OpenDoorState();
	}

	public function lock() : DoorState
	{
		return new LockedDoorState();
	}

	public function toString() : string
	{
		return self::CLOSED;
	}
}
```

#### Class `LockedDoorState`

```php
<?php declare(strict_types=1);
/**
 * @author Your Name <you@example.com>
 */

namespace YourVendor\YourProject\States;

use YourVendor\YourProject\States\Interfaces\DoorState;

/**
 * Class LockedDoorState
 * @package YourVendor\YourProject\States
 */
final class LockedDoorState extends AbstractDoorState
{
	public function unlock() : DoorState
	{
		return new ClosedDoorState();
	}

	public function toString() : string
	{
		return self::LOCKED;
	}
}
```

#### Class `OpenDoorState`

```php
<?php declare(strict_types=1);
/**
 * @author Your Name <you@example.com>
 */

namespace YourVendor\YourProject\States;

use YourVendor\YourProject\States\Interfaces\DoorState;

/**
 * Class OpenDoorState
 * @package YourVendor\YourProject\States
 */
final class OpenDoorState extends AbstractDoorState
{
	public function close() : DoorState
	{
		return new ClosedDoorState();
	}

	public function toString() : string
	{
		return self::OPEN;
	}
}
```

#### Class `Door`

```php
<?php declare(strict_types=1);
/**
 * @author Your Name <you@example.com>
 */

namespace YourVendor\YourProject;

use YourVendor\YourProject\States\Interfaces\DoorState;
use YourVendor\YourProject\States\Exceptions\IllegalStateTransition;
use YourVendor\YourProject\States\OpenDoorState;
use YourVendor\YourProject\States\ClosedDoorState;
use YourVendor\YourProject\States\LockedDoorState;

/**
 * Class Door
 * @package YourVendor\YourProject
 */
class Door
{
	/** @var DoorState */
	private $state;

	public function __construct(DoorState $state)
	{
		$this->setState($state);
	}

	private function setState(DoorState $state)
	{
		$this->state = $state;
	}

	/**
	 * @throws IllegalStateTransition
	 */
	public function open()
	{
		$this->setState($this->state->open());
	}

	/**
	 * @throws IllegalStateTransition
	 */
	public function close()
	{
		$this->setState($this->state->close());
	}

	/**
	 * @throws IllegalStateTransition
	 */
	public function lock()
	{
		$this->setState($this->state->lock());
	}

	/**
	 * @throws IllegalStateTransition
	 */
	public function unlock()
	{
		$this->setState($this->state->unlock());
	}

	public function isOpen() : bool
	{
		return ($this->state instanceof OpenDoorState);
	}

	public function isClosed() : bool
	{
		return ($this->state instanceof ClosedDoorState);
	}

	public function isLocked() : bool
	{
		return ($this->state instanceof LockedDoorState);
	}
}
```

#### Test class `ClosedDoorStateTest`

```php
<?php declare(strict_types=1);
/**
 * @author Your Name <you@example.com>
 */

namespace YourVendor\YourProject\Tests\Unit;

use YourVendor\YourProject\Door;
use YourVendor\YourProject\States\AbstractDoorState;
use YourVendor\YourProject\States\Exceptions\InvalidStateString;
use YourVendor\YourProject\States\Exceptions\IllegalStateTransition;
use YourVendor\YourProject\States\ClosedDoorState;

/**
 * Class ClosedDoorStateTest
 * @package YourVendor\YourProject\Tests\Unit
 */
final class ClosedDoorStateTest extends \PHPUnit\Framework\TestCase
{
    /** @var Door */
    private $door;

    protected function setUp()
    {
        $this->door = new Door(new ClosedDoorState());
	}

	public function testIsNotOpen()
	{
		$this->assertFalse($this->door->isOpen());
	}

	public function testIsClosed()
	{
		$this->assertTrue($this->door->isClosed());
	}

	public function testIsNotLocked()
	{
		$this->assertFalse($this->door->isLocked());
	}

	public function testCanBeOpened()
	{
		$this->door->open();

		$this->assertTrue($this->door->isOpen());
	}

	/**
	 * @expectedException \YourVendor\YourProject\States\Exceptions\IllegalStateTransition
	 */
	public function testCannotBeClosed()
	{
		try
		{
			$this->door->close();
		}
		catch(IllegalStateTransition $e)
		{
			$this->assertEquals('closed', $e->getDesiredState());

			throw $e;
		}
	}

	public function testCanBeLocked()
	{
		$this->door->lock();

		$this->assertTrue($this->door->isLocked());
	}

	/**
	 * @expectedException \YourVendor\YourProject\States\Exceptions\IllegalStateTransition
	 */
	public function testCannotBeUnlocked()
	{
		try
		{
			$this->door->unlock();
		}
		catch(IllegalStateTransition $e)
		{
			$this->assertEquals('closed', $e->getDesiredState());

			throw $e;
		}
	}

	public function testStateCanBeRepresentedAsString()
	{
		$state = new ClosedDoorState();

		$this->assertEquals('closed', (string)$state);
		$this->assertEquals('closed', $state->toString());
	}

	public function testCanBuildStateFromStringRepresentation()
	{
		$state = AbstractDoorState::fromString('closed');

		$this->assertInstanceOf(ClosedDoorState::class, $state);
	}

	/**
	 * @expectedException \YourVendor\YourProject\States\Exceptions\InvalidStateString
	 */
	public function testInvalidStateStringThrowsException()
	{
		try
		{
			AbstractDoorState::fromString('closed.invalid');
		}
		catch (InvalidStateString $e)
		{
			$this->assertEquals('closed.invalid', $e->getStateString());

			throw $e;
		}
	}
}
```

#### Test class `LockedDoorStateTest`

```php
<?php declare(strict_types=1);
/**
 * @author Your Name <you@example.com>
 */

namespace YourVendor\YourProject\Tests\Unit;

use YourVendor\YourProject\Door;
use YourVendor\YourProject\States\AbstractDoorState;
use YourVendor\YourProject\States\Exceptions\InvalidStateString;
use YourVendor\YourProject\States\Exceptions\IllegalStateTransition;
use YourVendor\YourProject\States\LockedDoorState;

/**
 * Class LockedDoorStateTest
 * @package YourVendor\YourProject\Tests\Unit
 */
final class LockedDoorStateTest extends \PHPUnit\Framework\TestCase
{
    /** @var Door */
    private $door;

    protected function setUp()
    {
        $this->door = new Door(new LockedDoorState());
	}

	public function testIsNotOpen()
	{
		$this->assertFalse($this->door->isOpen());
	}

	public function testIsNotClosed()
	{
		$this->assertFalse($this->door->isClosed());
	}

	public function testIsLocked()
	{
		$this->assertTrue($this->door->isLocked());
	}

	/**
	 * @expectedException \YourVendor\YourProject\States\Exceptions\IllegalStateTransition
	 */
	public function testCannotBeOpened()
	{
		try
		{
			$this->door->open();
		}
		catch(IllegalStateTransition $e)
		{
			$this->assertEquals('open', $e->getDesiredState());

			throw $e;
		}
	}

	/**
	 * @expectedException \YourVendor\YourProject\States\Exceptions\IllegalStateTransition
	 */
	public function testCannotBeClosed()
	{
		try
		{
			$this->door->close();
		}
		catch(IllegalStateTransition $e)
		{
			$this->assertEquals('closed', $e->getDesiredState());

			throw $e;
		}
	}

	/**
	 * @expectedException \YourVendor\YourProject\States\Exceptions\IllegalStateTransition
	 */
	public function testCannotBeLocked()
	{
		try
		{
			$this->door->lock();
		}
		catch(IllegalStateTransition $e)
		{
			$this->assertEquals('locked', $e->getDesiredState());

			throw $e;
		}
	}

	public function testCanBeUnlocked()
	{
		$this->door->unlock();

		$this->assertTrue($this->door->isClosed());
	}

	public function testStateCanBeRepresentedAsString()
	{
		$state = new LockedDoorState();

		$this->assertEquals('locked', (string)$state);
		$this->assertEquals('locked', $state->toString());
	}

	public function testCanBuildStateFromStringRepresentation()
	{
		$state = AbstractDoorState::fromString('locked');

		$this->assertInstanceOf(LockedDoorState::class, $state);
	}

	/**
	 * @expectedException \YourVendor\YourProject\States\Exceptions\InvalidStateString
	 */
	public function testInvalidStateStringThrowsException()
	{
		try
		{
			AbstractDoorState::fromString('locked.invalid');
		}
		catch (InvalidStateString $e)
		{
			$this->assertEquals('locked.invalid', $e->getStateString());

			throw $e;
		}
	}
}
```

#### Test class `OpenDoorStateTest`

```php
<?php declare(strict_types=1);
/**
 * @author Your Name <you@example.com>
 */

namespace YourVendor\YourProject\Tests\Unit;

use YourVendor\YourProject\Door;
use YourVendor\YourProject\States\AbstractDoorState;
use YourVendor\YourProject\States\Exceptions\InvalidStateString;
use YourVendor\YourProject\States\Exceptions\IllegalStateTransition;
use YourVendor\YourProject\States\OpenDoorState;

/**
 * Class OpenDoorStateTest
 * @package YourVendor\YourProject\Tests\Unit
 */
final class OpenDoorStateTest extends \PHPUnit\Framework\TestCase
{
    /** @var Door */
    private $door;

    protected function setUp()
    {
        $this->door = new Door(new OpenDoorState());
	}

	public function testIsOpen()
	{
		$this->assertTrue($this->door->isOpen());
	}

	public function testIsNotClosed()
	{
		$this->assertFalse($this->door->isClosed());
	}

	public function testIsNotLocked()
	{
		$this->assertFalse($this->door->isLocked());
	}

	/**
	 * @expectedException \YourVendor\YourProject\States\Exceptions\IllegalStateTransition
	 */
	public function testCannotBeOpened()
	{
		try
		{
			$this->door->open();
		}
		catch(IllegalStateTransition $e)
		{
			$this->assertEquals('open', $e->getDesiredState());

			throw $e;
		}
	}

	public function testCanBeClosed()
	{
		$this->door->close();

		$this->assertTrue($this->door->isClosed());
	}

	/**
	 * @expectedException \YourVendor\YourProject\States\Exceptions\IllegalStateTransition
	 */
	public function testCannotBeLocked()
	{
		try
		{
			$this->door->lock();
		}
		catch(IllegalStateTransition $e)
		{
			$this->assertEquals('locked', $e->getDesiredState());

			throw $e;
		}
	}

	/**
	 * @expectedException \YourVendor\YourProject\States\Exceptions\IllegalStateTransition
	 */
	public function testCannotBeUnlocked()
	{
		try
		{
			$this->door->unlock();
		}
		catch(IllegalStateTransition $e)
		{
			$this->assertEquals('closed', $e->getDesiredState());

			throw $e;
		}
	}

	public function testStateCanBeRepresentedAsString()
	{
		$state = new OpenDoorState();

		$this->assertEquals('open', (string)$state);
		$this->assertEquals('open', $state->toString());
	}

	public function testCanBuildStateFromStringRepresentation()
	{
		$state = AbstractDoorState::fromString('open');

		$this->assertInstanceOf(OpenDoorState::class, $state);
	}

	/**
	 * @expectedException \YourVendor\YourProject\States\Exceptions\InvalidStateString
	 */
	public function testInvalidStateStringThrowsException()
	{
		try
		{
			AbstractDoorState::fromString('open.invalid');
		}
		catch (InvalidStateString $e)
		{
			$this->assertEquals('open.invalid', $e->getStateString());

			throw $e;
		}
	}
}
```

### Test execution result with TestDox and text coverage summary

```
PHPUnit 6.0.6 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.1.1 with Xdebug 2.5.0
Configuration: /Users/hollodotme/Sites/StateMachineGenerator/build/phpunit.xml

YourVendor\YourProject\Tests\Unit\ClosedDoorState
 [x] Is not open
 [x] Is closed
 [x] Is not locked
 [x] Can be opened
 [x] Cannot be closed
 [x] Can be locked
 [x] Cannot be unlocked
 [x] State can be represented as string
 [x] Can build state from string representation
 [x] Invalid state string throws exception

YourVendor\YourProject\Tests\Unit\LockedDoorState
 [x] Is not open
 [x] Is not closed
 [x] Is locked
 [x] Cannot be opened
 [x] Cannot be closed
 [x] Cannot be locked
 [x] Can be unlocked
 [x] State can be represented as string
 [x] Can build state from string representation
 [x] Invalid state string throws exception

YourVendor\YourProject\Tests\Unit\OpenDoorState
 [x] Is open
 [x] Is not closed
 [x] Is not locked
 [x] Cannot be opened
 [x] Can be closed
 [x] Cannot be locked
 [x] Cannot be unlocked
 [x] State can be represented as string
 [x] Can build state from string representation
 [x] Invalid state string throws exception

Code Coverage Report Summary:
  Classes: 100.00% (7/7)
  Methods: 100.00% (26/26)
  Lines:   100.00% (42/42)
```
