<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator;

use hollodotme\StateMachineGenerator\ConsoleCommands\CreateSpecificationCommand;
use hollodotme\StateMachineGenerator\ConsoleCommands\GenerateStateMachineCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\RuntimeException;

require(__DIR__ . '/../vendor/autoload.php');

try
{
	$app = new Application( 'State machine generator', '@package_version@' );
	$app->addCommands(
		[
			new CreateSpecificationCommand( 'create:specfile' ),
			new GenerateStateMachineCommand( 'generate:machine' ),
		]
	);

	$exitCode = $app->run();

	exit( $exitCode );
}
catch ( RuntimeException $e )
{
	echo get_class( $e ), "\n";
	echo "Message: ", $e->getMessage(), "\n";
	echo $e->getTraceAsString();

	exit( 1 );
}
