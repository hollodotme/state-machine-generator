<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\ConsoleCommands;

use hollodotme\StateMachineGenerator\Generator\SpecificationParser;
use hollodotme\StateMachineGenerator\Generator\Types\SpecificationFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class GenerateStateMachineCommand
 * @package hollodotme\StateMachineGenerator\ConsoleCommands
 */
final class GenerateStateMachineCommand extends Command
{
	protected function configure()
	{
		$this->addArgument(
			'specfile',
			InputArgument::REQUIRED,
			'The XML file containing the state machine specification'
		);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) : int
	{
		$style    = new SymfonyStyle( $input, $output );
		$specFile = new SpecificationFile( $input->getArgument( 'specfile' ) );

		if ( !$specFile->exists() )
		{
			$style->error( 'Specification file not found: ' . (string)$specFile );

			return 1;
		}

		$parser = new SpecificationParser( $specFile );

		print_r( $parser->getOutputSettings() );
		print_r( $parser->getConfigurations() );
		print_r( $parser->getStates() );
		print_r( $parser->getOperations() );
		print_r( $parser->getQueries() );

		return 0;
	}
}
