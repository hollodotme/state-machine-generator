<?php declare(strict_types = 1);
/**
 * @author hollodotme
 */

namespace hollodotme\StateMachineGenerator\ConsoleCommands;

use hollodotme\StateMachineGenerator\Generator\AbstractGenerator;
use hollodotme\StateMachineGenerator\Generator\AbstractStateClassGenerator;
use hollodotme\StateMachineGenerator\Generator\MainClassGenerator;
use hollodotme\StateMachineGenerator\Generator\Specification;
use hollodotme\StateMachineGenerator\Generator\StateClassGenerator;
use hollodotme\StateMachineGenerator\Generator\StateInterfaceGenerator;
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

		$specification = new Specification( $specFile );
		$generators    = [
			new StateInterfaceGenerator( $specification ),
			new AbstractStateClassGenerator( $specification ),
			new MainClassGenerator( $specification ),
		];

		foreach ( $specification->getStates() as $state )
		{
			$generators[] = new StateClassGenerator( $specification, $state );
		}

		/** @var AbstractGenerator $generator */
		foreach ( $generators as $generator )
		{
			$outputFile = $generator->generate();

			if ( !file_exists( $outputFile->getDir() ) )
			{
				@mkdir( $outputFile->getDir(), 0777, true );
			}

			$bytesWritten = file_put_contents( $outputFile->getFilePath(), $outputFile->getContent() );

			if ( $bytesWritten > 0 )
			{
				$style->writeln( "Generated file {$outputFile->getFilePath()} ({$bytesWritten} bytes)" );
			}
			else
			{
				$style->error( "Could not write file {$outputFile->getFilePath()}" );
			}
		}

		return 0;
	}
}
